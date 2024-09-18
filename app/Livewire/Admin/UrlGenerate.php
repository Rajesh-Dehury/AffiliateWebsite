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
        $this->amazon_partner_tag = env('AMAZON_PARTNER_TAG');
    }

    public function updatedPrice()
    {
        if (strpos($this->price, '₹ ') === false) {
            $this->price = '₹ ' . $this->price;
        }
        $this->generateWpPost();
    }
    public function updatedMrp()
    {
        if (strpos($this->mrp, '₹ ') === false) {
            $this->mrp = '₹ ' . $this->mrp;
        }

        $this->generateWpPost();
    }
    public function updatedSavingPercent()
    {
        if (strpos($this->saving_percent, '₹ ') === false) {
            $this->saving_percent = '₹ ' . $this->saving_percent;
        }
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
        $crawler = $client->request('GET', $this->url);

        // Check for 'pd_rd_i' parameter in the query string
        if (isset($query_params['pd_rd_i'])) {
            // If 'pd_rd_i' parameter is present, use it as the ASIN
            $this->asin = $query_params['pd_rd_i'];
        } else {
            // If not, look for the ASIN in the URL path
            $path_segments = explode('/', $parsed_url['path']);

            foreach ($path_segments as $segment) {
                // Look for a segment that matches the ASIN pattern
                if (preg_match('/^B[A-Z0-9]{9}$/', $segment)) {
                    $this->asin = $segment;
                    break;
                }
            }

            // If ASIN is not found in the URL, try to scrape it from the page
            if (!$this->asin) {
                $this->asin = $crawler->filter('input[name="ASIN"]')->attr('value');
            }
        }

        // Construct a new Amazon URL with the affiliate tag
        // $this->new_url = "https://www.amazon.in/dp/{$this->asin}?tag=codewithrd-21&linkCode=ogi&th=1&psc=1";
        $this->new_url = "https://www.amazon.in/dp/{$this->asin}?psc=1&tag=codewithrd-21";

        // Scrape the product title
        try {
            $product_title = $crawler->filter('#productTitle')->text();
            $this->product_title = trim($product_title);
        } catch (\Exception $e) {
        }

        // Scrape the price from the page
        try {
            // Try to get the price from the '.a-price-whole' and '.a-price-decimal' if they exist
            $whole_price = $crawler->filter('.a-price-whole')->text();
            $decimal_price = $crawler->filter('.a-price-decimal')->text();
            $this->price = trim($whole_price) . (empty($decimal_price) ? '' : '.' . trim($decimal_price));
        } catch (\Exception $e) {
            // Handle or log the exception if needed
            // $this->price = 'Price not found';
        }

        try {
            // Extract the MRP from the span with class 'a-offscreen' within 'a-price a-text-price'
            $this->mrp = $crawler->filter('.a-price.a-text-price .a-offscreen')->first()->text();
        } catch (\Exception $e) {
            // Handle or log the exception if needed
            // $this->mrp = 'MRP not found';
        }

        try {
            // Get the saving percentage from '.savingsPercentage' if it exists
            $this->saving_percent = $crawler->filter('.savingsPercentage')->text();
        } catch (\Exception $e) {
            // Handle or log the exception if needed
            // $this->saving_percent = 'Saving percentage not found';
        }

        // Construct the WordPress post content
        $this->generateWpPost();
    }

    public function generateWpPost()
    {
        $this->wp_post = "$this->product_title";
        if ($this->mrp != "") {
            $this->wp_post .= "\r\n \r\n";
            $this->wp_post .= "MRP: ~{$this->mrp}~/- ⬇️ Lowest Price";
        }
        if ($this->price != "") {
            $this->wp_post .= "\r\n \r\n";
            $this->wp_post .= "DEAL: *{$this->price}/- ";
        }
        if ($this->saving_percent != "") {
            $this->wp_post .= "({$this->saving_percent})* 🕛";
        }
        $this->wp_post .= "\r\n \r\n";
        if ($this->new_url != "") {
            $this->wp_post .= "LINK : {$this->new_url}";
        }
    }

    public function sendTelegram()
    {
        $chatId = '-1002191566525';
        if (is_null($this->wp_post)) {
            session()->flash('error', "No message Generated");
            return;
        }
        $message = $this->wp_post;
        app('App\Http\Controllers\TelegramBotController')->sendMessageToGroup($chatId, $message);
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
