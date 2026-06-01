<?php

namespace App\Livewire\Admin;

use App\Models\AmazonDeals;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Symfony\Component\BrowserKit\HttpBrowser;

class UrlGenerate extends Component
{
    public $url;
    public $asin;
    public $amazon_partner_tag;
    public $new_url;
    public $product_title;
    public $price;
    public $mrp;
    public $saving_percent;
    public $wp_post;

    protected $rules = [
        'url' => 'required|url',
    ];

    public function mount()
    {
        $this->amazon_partner_tag = config('services.amazon.partner_tag', env('AMAZON_PARTNER_TAG', 'codewithrd-21'));
    }

    public function updatedPrice()
    {
        $this->generateWpPost();
    }
    public function updatedMrp()
    {
        $this->generateWpPost();
    }
    public function updatedSavingPercent()
    {
        $this->generateWpPost();
    }

    public function scrape()
    {
        // Validate the input URL
        $this->validate([
            'url' => 'required|url',
        ]);

        // Parse the URL
        $parsed_url = parse_url($this->url);
        parse_str($parsed_url['query'] ?? '', $query_params);

        $client = new HttpBrowser();
        
        // Add User Agent to avoid basic blocks
        $client->setServerParameter('HTTP_USER_AGENT', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36');
        
        try {
            $crawler = $client->request('GET', $this->url);
        } catch (\Exception $e) {
            session()->flash('error', 'Could not access Amazon page.');
            return;
        }

        // Check for 'pd_rd_i' parameter in the query string
        if (isset($query_params['pd_rd_i'])) {
            // If 'pd_rd_i' parameter is present, use it as the ASIN
            $this->asin = $query_params['pd_rd_i'];
        } else {
            // If not, look for the ASIN in the URL path
            $path_segments = explode('/', $parsed_url['path'] ?? '');

            foreach ($path_segments as $segment) {
                // Look for a segment that matches the ASIN pattern
                if (preg_match('/^B[A-Z0-9]{9}$/', $segment)) {
                    $this->asin = $segment;
                    break;
                }
            }

            // If ASIN is not found in the URL, try to scrape it from the page
            if (!$this->asin) {
                try {
                    $this->asin = $crawler->filter('input[name="ASIN"]')->attr('value');
                } catch (\Exception $e) {}
            }
        }

        if (!$this->asin) {
             session()->flash('error', 'Could not find ASIN in URL.');
             return;
        }

        // Construct a new Amazon URL with the affiliate tag
        $this->new_url = "https://www.amazon.in/dp/{$this->asin}?tag={$this->amazon_partner_tag}";

        // Scrape the product title
        try {
            $product_title = $crawler->filter('#productTitle')->text();
            $this->product_title = trim($product_title);
        } catch (\Exception $e) {}

        // Scrape the price from the page
        try {
            $this->price = $crawler->filter('.a-price-whole')->first()->text();
            $this->price = preg_replace('/[^0-9]/', '', $this->price);
        } catch (\Exception $e) {}

        try {
            $mrp_text = $crawler->filter('.a-price.a-text-price .a-offscreen')->first()->text();
            $this->mrp = preg_replace('/[^0-9]/', '', $mrp_text);
        } catch (\Exception $e) {}

        try {
            $this->saving_percent = $crawler->filter('.savingsPercentage')->first()->text();
            $this->saving_percent = preg_replace('/[^0-9]/', '', $this->saving_percent);
        } catch (\Exception $e) {}

        // Construct the post content
        $this->generateWpPost();
    }

    public function generateWpPost()
    {
        $mrp = $this->mrp ? '₹' . $this->mrp : '';
        $price = $this->price ? '₹' . $this->price : '';
        $saving = $this->saving_percent ? $this->saving_percent . '% Off' : '';

        $this->wp_post = "[{$saving}] {$this->product_title}\n\nMrp: {$mrp} | DEAL: {$price}\n\nLINK: {$this->new_url}";
    }

    public function sendTelegram()
    {
        $chatId = config('services.telegram.chat_id', '-1002191566525');
        if (is_null($this->wp_post)) {
            session()->flash('error', "No message Generated");
            return;
        }
        app('App\Http\Controllers\TelegramBotController')->sendMessageToGroup($chatId, $this->wp_post);
    }
    
    public function sendWhatsapp()
    {
        if (is_null($this->wp_post)) {
            session()->flash('error', "No message Generated");
            return;
        }
        
        $encodedMessage = urlencode($this->wp_post);
        $this->dispatch('open-link', url: "https://api.whatsapp.com/send?text={$encodedMessage}");
    }

    public function postToFacebookPage()
    {
        $pageId = '411901358668877'; //Page DealsDay

        if (is_null($this->wp_post)) {
            session()->flash('error', "No message Generated");
            return;
        }
        try {
            $response = Http::post("https://graph.facebook.com/v20.0/{$pageId}/feed", [
                'message' => $this->wp_post,
                'access_token' => env('FACEBOOK_PAGE_ACCESS_TOKEN'),
            ]);

            if ($response->successful()) {
                $graphNode = $response->json();
                // Session::flash('success', 'Post ID: ' . $graphNode['id']);
                Session::flash('success', 'Posted to Facebook');
            } else {
                Session::flash('error', 'Failed to post to Facebook: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Exception when posting to Facebook: ' . $e->getMessage());
            Session::flash('error', 'Exception when posting to Facebook: ' . $e->getMessage());
        }

        return redirect()->back();
    }

    public function savePost()
    {
        $this->validate([
            'asin' => 'required',
            'new_url' => 'required',
            'product_title' => 'required',
        ]);

        AmazonDeals::updateOrCreate(
            ['product_asin' => $this->asin],
            [
                'product_asin' => $this->asin,
                'product_asin_hash' => "",
                'detail_page_url' => $this->new_url,
                'primary_large_url' => asset('default_image.png'),
                'product_title' => $this->product_title,
                'mrp' => $this->mrp,
                'offer_price' => $this->price,
                'saving_percent' => $this->saving_percent,
                'saving_amount' => "",
                'features_editor' => "",
                'our_link' => $this->new_url,
                'json' => "",
                'wp_post' => $this->wp_post,
                'our_post' => $this->wp_post,
            ]
        );
        Session::flash('success', 'Posted to DealsDay24.in');
    }

    public function resetT()
    {
        $this->reset();
    }

    public function render()
    {
        return view('livewire.admin.url-generate')->layout('components.admin-layout');
    }
}
