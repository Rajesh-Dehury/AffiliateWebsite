<?php

namespace App\Livewire\Admin;

error_reporting(E_ALL);
ini_set('display_errors', 1);

use App\Models\AmazonDeals;
use App\Services\AwsV4;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\BrowserKit\HttpBrowser;

class GetAmazonProductDetails extends Component
{
    public $url;
    public $asin;
    public $response;

    private $accessKey = 'AKIAJLWHKGTNUQ4HPAPQ'; // Replace with your actual access key
    private $secretKey = 'L/SeFQI1LCQ6GE3Q/B8iXXAkL1CRcpOzLqlMnXyM'; // Replace with your actual secret key
    private $region = 'eu-west-1';
    private $service = 'ProductAdvertisingAPI';
    private $host = 'webservices.amazon.in';
    private $uriPath = '/paapi5/getitems';

    // fetch data 
    public $json;
    public $product_asin;
    public $product_asin_hash;
    public $detail_page_url;
    public $primary_large_url;
    public $product_title;
    public $mrp;
    public $offer_price;
    public $saving_percent;
    public $saving_amount;
    public $features_editor;
    public $our_link;
    public $wp_post;
    public $our_post;

    public function render()
    {
        return view('livewire.admin.get-amazon-product-details')->layout('components.admin-layout');
    }

    public function resetT()
    {
        $this->reset();
    }

    public function fetchProductDetails()
    {
        $awsService = new AwsV4($this->accessKey, $this->secretKey);
        $awsService->setRegionName($this->region);
        $awsService->setServiceName($this->service);
        $awsService->setPath($this->uriPath);
        $awsService->setRequestMethod('POST');

        $payload = json_encode([
            'ItemIds' => [$this->asin],
            'Resources' => [
                'BrowseNodeInfo.BrowseNodes',
                'BrowseNodeInfo.BrowseNodes.Ancestor',
                'BrowseNodeInfo.BrowseNodes.SalesRank',
                'BrowseNodeInfo.WebsiteSalesRank',
                'CustomerReviews.Count',
                'CustomerReviews.StarRating',
                'Images.Primary.Small',
                'Images.Primary.Medium',
                'Images.Primary.Large',
                'Images.Primary.HighRes',
                'Images.Variants.Small',
                'Images.Variants.Medium',
                'Images.Variants.Large',
                'Images.Variants.HighRes',
                'ItemInfo.ByLineInfo',
                'ItemInfo.ContentInfo',
                'ItemInfo.ContentRating',
                'ItemInfo.Classifications',
                'ItemInfo.ExternalIds',
                'ItemInfo.Features',
                'ItemInfo.ManufactureInfo',
                'ItemInfo.ProductInfo',
                'ItemInfo.TechnicalInfo',
                'ItemInfo.Title',
                'ItemInfo.TradeInInfo',
                'Offers.Listings.Availability.MaxOrderQuantity',
                'Offers.Listings.Availability.Message',
                'Offers.Listings.Availability.MinOrderQuantity',
                'Offers.Listings.Availability.Type',
                'Offers.Listings.Condition',
                'Offers.Listings.Condition.ConditionNote',
                'Offers.Listings.Condition.SubCondition',
                'Offers.Listings.DeliveryInfo.IsAmazonFulfilled',
                'Offers.Listings.DeliveryInfo.IsFreeShippingEligible',
                'Offers.Listings.DeliveryInfo.IsPrimeEligible',
                'Offers.Listings.DeliveryInfo.ShippingCharges',
                'Offers.Listings.IsBuyBoxWinner',
                'Offers.Listings.LoyaltyPoints.Points',
                'Offers.Listings.MerchantInfo',
                'Offers.Listings.Price',
                'Offers.Listings.ProgramEligibility.IsPrimeExclusive',
                'Offers.Listings.ProgramEligibility.IsPrimePantry',
                'Offers.Listings.Promotions',
                'Offers.Listings.SavingBasis',
                'Offers.Summaries.HighestPrice',
                'Offers.Summaries.LowestPrice',
                'Offers.Summaries.OfferCount',
                'ParentASIN',
                'RentalOffers.Listings.Availability.MaxOrderQuantity',
                'RentalOffers.Listings.Availability.Message',
                'RentalOffers.Listings.Availability.MinOrderQuantity',
                'RentalOffers.Listings.Availability.Type',
                'RentalOffers.Listings.BasePrice',
                'RentalOffers.Listings.Condition',
                'RentalOffers.Listings.Condition.ConditionNote',
                'RentalOffers.Listings.Condition.SubCondition',
                'RentalOffers.Listings.DeliveryInfo.IsAmazonFulfilled',
                'RentalOffers.Listings.DeliveryInfo.IsFreeShippingEligible',
                'RentalOffers.Listings.DeliveryInfo.IsPrimeEligible',
                'RentalOffers.Listings.DeliveryInfo.ShippingCharges',
                'RentalOffers.Listings.MerchantInfo'
            ],
            'PartnerTag' => 'codewithrd-21',
            'PartnerType' => 'Associates',
            'Marketplace' => 'www.amazon.in'
        ]);

        $awsService->setPayload($payload);
        $awsService->addHeader('content-encoding', 'amz-1.0');
        $awsService->addHeader('content-type', 'application/json; charset=utf-8');
        $awsService->addHeader('host', $this->host);
        $awsService->addHeader('x-amz-target', 'com.amazon.paapi5.v1.ProductAdvertisingAPIv1.GetItems');

        $headers = $awsService->getHeaders();

        // Use Guzzle to make the request
        $client = new Client([
            'base_uri' => 'https://' . $this->host,
            'verify' => false // Disable SSL verification, not recommended for production
        ]);

        try {
            $response = $client->post($this->uriPath, [
                'headers' => $headers, // $headers should be an associative array
                'json' => json_decode($payload, true) // Convert payload to an array
            ]);

            $this->json = $response->getBody()->getContents();
            $this->response = json_decode($this->json, true);

            $item = $this->response['ItemsResult']['Items'][0];
            $this->product_asin = $item['ASIN'];
            $this->product_asin_hash = Hash::make($this->product_asin);
            $this->our_link = route('open.az.prod', $this->product_asin);

            $this->detail_page_url = $item['DetailPageURL'] ?? "";
            $this->primary_large_url = $item['Images']['Primary']['Large']['URL'] ?? "";
            $this->product_title = $item['ItemInfo']['Title']['DisplayValue'] ?? "";
            $this->mrp = $item['Offers']['Listings'][0]['SavingBasis']['DisplayAmount'] ?? "";
            $this->offer_price = $item['Offers']['Listings'][0]['Price']['DisplayAmount'] ?? "";
            $this->saving_percent = $item['Offers']['Listings'][0]['Price']['Savings']['Percentage'] ?? "";
            $this->saving_amount = $item['Offers']['Listings'][0]['Price']['Savings']['Amount'] ?? "";

            // WP Post
            $this->wp_post = "$this->product_title";
            if ($this->mrp != "") {
                $this->wp_post .= "\r\n \r\n";
                $this->wp_post .= "MRP: ~{$this->mrp}~/- âœ… Lowest Price";
            }
            if ($this->offer_price != "") {
                $this->wp_post .= "\r\n \r\n";
                $this->wp_post .= "DEAL: *{$this->offer_price}/- ";
            }
            if ($this->saving_percent != "") {
                $this->wp_post .= "({$this->saving_percent}%)* ðŸŽŠ";
            }
            $this->wp_post .= "\r\n \r\n";
            if ($this->detail_page_url != "") {
                $this->wp_post .= "LINK : {$this->detail_page_url}";
            }

            // Our Post
            $this->our_post = "$this->product_title";
            if ($this->mrp != "") {
                $this->our_post .= "\r\n \r\n";
                $this->our_post .= "MRP: ~{$this->mrp}~/- âœ… Lowest Price";
            }
            if ($this->offer_price != "") {
                $this->our_post .= "\r\n \r\n";
                $this->our_post .= "DEAL: *{$this->offer_price}/- ";
            }
            if ($this->saving_percent != "") {
                $this->our_post .= "({$this->saving_percent}%)* ðŸŽŠ";
            }
            $this->our_post .= "\r\n \r\n";
            if ($this->our_link != "") {
                $this->our_post .= "LINK : {$this->our_link}";
            }

            $displayValues = $item['ItemInfo']['Features']['DisplayValues'] ?? null;

            if ($displayValues) {
                $this->features_editor .= "<ul>\n";

                foreach ($displayValues as $key => $dv) {
                    $this->features_editor .= "<li>{$dv}</li>\n";
                }
                $this->features_editor .= "</ul>\n";
            }
        } catch (RequestException $e) {
            $this->response = ['error' => $e->getMessage()];
        }
    }

    public function scrape()
    {
        $this->validate([
            'url' => 'required|url',
        ]);

        // Parse the URL to check for 'pd_rd_i' parameter
        $parsed_url = parse_url($this->url);
        parse_str($parsed_url['query'] ?? '', $query_params);

        if (isset($query_params['pd_rd_i'])) {
            // If 'pd_rd_i' parameter is present, use it as the ASIN
            $this->asin = $query_params['pd_rd_i'];
        } else {
            // Otherwise, scrape the ASIN from the page
            $client = new HttpBrowser();
            $crawler = $client->request('GET', $this->url);

            $this->asin = $crawler->filter('input[name="ASIN"]')->attr('value');
        }

        if ($this->asin) {
            $this->fetchProductDetails();
        }
    }

    public function savePost()
    {
        $this->validate([
            'product_asin' => 'required',
            'detail_page_url' => 'required',
            'product_title' => 'required',
        ]);

        AmazonDeals::updateOrCreate(
            ['product_asin' => $this->product_asin],
            [
                'product_asin' => $this->product_asin,
                'product_asin_hash' => $this->product_asin_hash,
                'detail_page_url' => $this->detail_page_url,
                'primary_large_url' => $this->primary_large_url,
                'product_title' => $this->product_title,
                'mrp' => $this->mrp,
                'offer_price' => $this->offer_price,
                'saving_percent' => $this->saving_percent,
                'saving_amount' => $this->saving_amount,
                'features_editor' => $this->features_editor,
                'our_link' => $this->our_link,
                'json' => $this->json,
                'wp_post' => $this->wp_post,
                'our_post' => $this->our_post,
            ]
        );
    }
}
