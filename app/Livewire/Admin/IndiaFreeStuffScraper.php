<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Services\IndiaFreeStuffScraperService;
use App\Models\AmazonDeals;
use Illuminate\Support\Str;

class IndiaFreeStuffScraper extends Component
{
    public $scrapedDeals = [];
    public $isScraping = false;

    public function scrape()
    {
        $this->isScraping = true;
        $this->scrapedDeals = [];

        try {
            $scraper = new IndiaFreeStuffScraperService(new \App\Services\AmazonApiService());
            $this->scrapedDeals = $scraper->scrape(20);
        } catch (\Exception $e) {
            session()->flash('error', 'Scraping failed: ' . $e->getMessage());
        }

        $this->isScraping = false;
    }

    public function getPostText($index): string
    {
        if (!isset($this->scrapedDeals[$index])) return '';

        $deal = $this->scrapedDeals[$index];
        $mrp = '₹' . number_format($deal['mrp'], 0, '.', ',');
        $price = '₹' . number_format($deal['offer_price'], 0, '.', ',');
        $discount = $deal['discount'] . '% Off';
        $asin = $deal['asin'] ?? '';

        $tag = config('services.amazon.partner_tag', 'codewithrd-21');
        $link = $asin
            ? "https://www.amazon.in/dp/{$asin}?tag={$tag}"
            : $deal['deal_url'];

        return "[{$discount}] {$deal['title']}\n\nMrp: {$mrp} | DEAL: {$price}\n\nLINK: {$link}";
    }

    public function postToWebsite($index): void
    {
        if (!isset($this->scrapedDeals[$index])) return;

        $deal = $this->scrapedDeals[$index];

        if (empty($deal['asin'])) {
            session()->flash('error', 'Cannot save to website: ASIN not found.');
            return;
        }

        if (AmazonDeals::where('product_asin', $deal['asin'])->exists()) {
            session()->flash('error', 'Deal already exists on website.');
            return;
        }

        $tag = config('services.amazon.partner_tag', 'codewithrd-21');
        $directLink = "https://www.amazon.in/dp/{$deal['asin']}?tag={$tag}";

        $slug = Str::slug($deal['title']);
        $count = AmazonDeals::where('slug', 'LIKE', "{$slug}%")->count();
        $finalSlug = $count ? "{$slug}-{$count}" : $slug;

        $postText = $this->getPostText($index);

        AmazonDeals::create([
            'product_asin'    => $deal['asin'],
            'product_asin_hash' => encrypt($deal['asin'] ?? ''),
            'slug'            => $finalSlug,
            'detail_page_url' => $directLink,
            'primary_large_url' => $deal['image_url'] ?? asset('default_image.png'),
            'product_title'   => $deal['title'],
            'mrp'             => '₹' . $deal['mrp'],
            'offer_price'     => '₹' . $deal['offer_price'],
            'saving_percent'  => (string) $deal['discount'],
            'our_link'        => $directLink,
            'our_post'        => $postText,
            'wp_post'         => $postText,
        ]);

        session()->flash('success', "Deal saved to website!");
    }

    public function postToTelegram($index): void
    {
        $text = $this->getPostText($index);
        $chatId = config('services.telegram.chat_id', '-1002191566525');

        try {
            app('App\Http\Controllers\TelegramBotController')->sendMessageToGroup($chatId, $text);
            session()->flash('success', "Posted to Telegram!");
        } catch (\Exception $e) {
            session()->flash('error', "Failed to post to Telegram.");
        }
    }

    public function postToWhatsapp($index): void
    {
        $text = $this->getPostText($index);
        $encodedMessage = urlencode($text);
        $this->dispatch('open-link', url: "https://api.whatsapp.com/send?text={$encodedMessage}");
    }

    public function getAllPostText(): string
    {
        $separator = "\n\n──────────────────\n\n";
        return collect($this->scrapedDeals)
            ->take(10)
            ->map(fn($deal, $i) => $this->getPostText($i))
            ->implode($separator);
    }

    public function postAllToTelegram(): void
    {
        if (empty($this->scrapedDeals)) return;

        $chatId = config('services.telegram.chat_id', '-1002191566525');
        $text = $this->getAllPostText();

        try {
            app('App\Http\Controllers\TelegramBotController')->sendMessageToGroup($chatId, $text);
            session()->flash('success', "Top 10 deals posted to Telegram!");
        } catch (\Exception $e) {
            session()->flash('error', "Failed to post all to Telegram.");
        }
    }

    public function render()
    {
        return view('livewire.admin.india-free-stuff-scraper')
            ->layout('components.admin-layout');
    }
}
