<?php

namespace App\Livewire\Admin;

use App\Models\AmazonDeals;
use App\Services\GeminiService;
use App\Services\AmazonApiService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Component;
use Telegram\Bot\Laravel\Facades\Telegram;

class DealFinder extends Component
{
    public string $category    = 'all';
    public string $brands      = '';
    public int    $minDiscount = 50;
    public float  $minRating    = 4.0;
    public string $priceRange  = '5000 to 15000';
    public string $audience    = 'general buyers in India';
    public bool   $primeOnly   = false;
    public string $sortBy      = 'discount'; // discount, price_low, price_high, rating

    public array  $deals  = [];

    public bool   $loading = false;
    public bool   $verifying = false;
    public string $error   = '';
    public int    $sessionId = 0;

    public bool   $useDirectLinks = true;

    public function fetchDeals(): void
    {
        $this->loading = true;
        $this->error   = '';
        $this->deals   = [];

        try {
            $service = new GeminiService();
            $this->deals = $service->fetchDeals([
                'category'     => $this->category,
                'brands'       => $this->brands,
                'min_discount' => $this->minDiscount,
                'min_rating'   => $this->minRating,
                'price_range'  => $this->priceRange,
                'audience'     => $this->audience,
                'prime_only'   => $this->primeOnly,
                'sort_by'      => $this->sortBy,
            ]);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            Log::error('DealFinder error', ['error' => $e->getMessage()]);
        } finally {
            $this->loading = false;
        }
    }

    public function verifyAvailability(): void
    {
        if (empty($this->deals)) return;

        $this->verifying = true;

        try {
            $asins = collect($this->deals)->pluck('asin')->toArray();
            $apiService = new AmazonApiService();
            $liveItems = $apiService->getItems($asins);

            $liveMap = collect($liveItems)->keyBy(fn($item) => strtoupper($item['ASIN']));

            foreach ($this->deals as $i => $deal) {
                $asin = strtoupper($deal['asin'] ?? '');
                if ($liveMap->has($asin)) {
                    $item = $liveMap->get($asin);
                    $this->deals[$i]['isAvailable'] = true;
                    // Update prices if live data exists
                    $priceData = $item['Offers']['Listings'][0]['Price'] ?? null;
                    if ($priceData) {
                        $this->deals[$i]['currentPrice'] = (int) $priceData['Amount'];
                        $this->deals[$i]['discountPercent'] = (int) ($priceData['Savings']['Percentage'] ?? $deal['discountPercent']);
                    }
                    $this->deals[$i]['imageUrl'] = $item['Images']['Primary']['Large']['URL'] ?? $deal['imageUrl'];
                } else {
                    $this->deals[$i]['isAvailable'] = false;
                }
            }

            $availableCount = collect($this->deals)->where('isAvailable', true)->count();
            if ($availableCount === 0) {
                Session::flash('error', "None of these products are currently available on Amazon.");
            } else {
                Session::flash('success', "Found {$availableCount} available products.");
            }
            } catch (\Exception $e) {
            Log::error('Verification error', ['error' => $e->getMessage()]);
            Session::flash('error', "Failed to verify with Amazon API. Check your keys.");
            } finally {
            $this->verifying = false;
            }
            }

            public function removeUnavailable(): void
            {
            $this->deals = collect($this->deals)
            ->filter(fn($deal) => ($deal['isAvailable'] ?? true))
            ->values()
            ->toArray();

            Session::flash('success', 'Unavailable items removed.');
            }

    public function saveDeals(): void
    {
        if (empty($this->deals)) {
            Session::flash('error', 'No deals to save. Fetch deals first.');
            return;
        }

        $saved = 0;
        foreach ($this->deals as $deal) {
            $this->saveDealToDb($deal);
            $saved++;
        }

        $this->sessionId = 1; // Simple flag to indicate deals were saved
        Session::flash('success', "{$saved} deals saved to DealsDay24.in");
    }

    public function postToWebsite(int $index): void
    {
        if (!isset($this->deals[$index])) return;

        $deal = $this->deals[$index];
        $this->saveDealToDb($deal);

        Session::flash('success', "Deal posted to website!");
    }

    private function saveDealToDb(array $deal): void
    {
        $name = $deal['name'] ?? 'Unknown Product';
        $slug = Str::slug($name);
        $count = AmazonDeals::where('slug', 'LIKE', "{$slug}%")->count();
        $finalSlug = $count ? "{$slug}-{$count}" : $slug;

        $mrp  = '₹' . number_format($deal['originalPrice'] ?? 0, 0, '.', ',');
        $dealPrice = '₹' . number_format($deal['currentPrice'] ?? 0, 0, '.', ',');
        $discount = $deal['discountPercent'] ?? 0;

        $postText = "[{$discount}% Off] {$name}\n\nMrp: {$mrp} | DEAL: {$dealPrice}\n\nLINK: {$deal['affiliate_link']}";

        AmazonDeals::create([
            'product_asin'    => $deal['asin'] ?? null,
            'product_asin_hash' => encrypt($deal['asin'] ?? ''),
            'slug'            => $finalSlug,
            'detail_page_url' => $deal['affiliate_link'] ?? '',
            'primary_large_url' => $deal['imageUrl'] ?? null,
            'product_title'   => $name,
            'mrp'             => $mrp,
            'offer_price'     => $dealPrice,
            'saving_percent'  => (string) $discount,
            'our_link'        => route('open.az.prod', $deal['asin'] ?? ''),
            'our_post'        => $postText,
            'wp_post'         => $postText,
            'json'            => json_encode($deal),
        ]);
    }

    public function postToTelegram(int $index): void
    {
        if (!isset($this->deals[$index])) return;

        $deal = $this->deals[$index];
        
        // Ensure deal is in DB for redirect link to work
        $this->saveDealToDb($deal);
        
        $text = $this->getPostText($deal);
        $chatId = config('services.telegram.chat_id');

        try {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => $text,
            ]);
            Session::flash('success', "Deal posted to Telegram!");
        } catch (\Exception $e) {
            Log::error('Telegram post error', ['error' => $e->getMessage()]);
            Session::flash('error', "Failed to post to Telegram.");
        }
    }

    public function postAllToTelegram(): void
    {
        if (empty($this->deals)) return;

        $chatId = config('services.telegram.chat_id');
        
        // Save all first
        foreach ($this->deals as $deal) {
            $this->saveDealToDb($deal);
        }
        
        $text = $this->getAllPostText();

        try {
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => $text,
            ]);
            Session::flash('success', "All deals posted to Telegram!");
        } catch (\Exception $e) {
            Log::error('Telegram post all error', ['error' => $e->getMessage()]);
            Session::flash('error', "Failed to post all to Telegram.");
        }
    }

    public function getPostText($deal): string
    {
        $mrp  = '₹' . number_format($deal['originalPrice'] ?? 0, 0, '.', ',');
        $dealPrice = '₹' . number_format($deal['currentPrice'] ?? 0, 0, '.', ',');
        $discount = $deal['discountPercent'] ?? 0;
        $asin = $deal['asin'] ?? '';
        
        $tag = config('services.amazon.partner_tag', 'codewithrd-21');
        $link = "https://www.amazon.in/dp/{$asin}?tag={$tag}";

        return "[{$discount}% Off] {$deal['name']}\n\nMrp: {$mrp} | DEAL: {$dealPrice}\n\nLINK: {$link}";
    }

    public function getAllPostText(): string
    {
        $separator = "\n\n──────────────────\n\n";
        return collect($this->deals)
            ->map(fn($deal) => $this->getPostText($deal))
            ->implode($separator);
    }

    public function render()
    {
        return view('livewire.admin.deal-finder')->layout('components.admin-layout');
    }
}
