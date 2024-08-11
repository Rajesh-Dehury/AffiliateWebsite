<?php

namespace App\Livewire\Admin;

use App\Models\AmazonDeals;
use App\Services\AwsV4;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
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

    public function render()
    {
        return view('livewire.admin.get-amazon-product-details')->layout('components.admin-layout');
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
        $headerString = "";
        foreach ($headers as $key => $value) {
            $headerString .= $key . ': ' . $value . "\r\n";
        }

        $params = [
            'http' => [
                'header' => $headerString,
                'method' => 'POST',
                'content' => $payload
            ]
        ];

        $context = stream_context_create($params);

        try {
            $fp = @fopen('https://' . $this->host . $this->uriPath, 'rb', false, $context);
            if (!$fp) {
                throw new \Exception("Unable to connect to API.");
            }

            $response = @stream_get_contents($fp);
            if ($response === false) {
                throw new \Exception("Error reading response.");
            }

            $this->response = json_decode($response, true);

            $item = $this->response['ItemsResult']['Items'][0];
            $this->product_asin = $item['ASIN'];
            $this->detail_page_url = $item['DetailPageURL'];
            $this->primary_large_url = $item['Images']['Primary']['Large']['URL'];
            $this->product_title = $item['ItemInfo']['Title']['DisplayValue'];
            $this->mrp = $item['Offers']['Listings'][0]['SavingBasis']['DisplayAmount'];
            $this->offer_price = $item['Offers']['Listings'][0]['Price']['DisplayAmount'];
            $this->saving_percent = $item['Offers']['Listings'][0]['Price']['Savings']['Percentage'];
            $this->saving_amount = $item['Offers']['Listings'][0]['Price']['Savings']['Amount'];


            foreach ($item['ItemInfo']['Features']['DisplayValues'] as $key => $dv) {
                $this->features_editor .= "{$key} : {$dv}<br>\n";
            }
        } catch (\Exception $e) {
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
                'product_asin_hash' => Hash::make($this->product_asin_hash),
                'detail_page_url' => $this->detail_page_url,
                'primary_large_url' => $this->primary_large_url,
                'product_title' => $this->product_title,
                'mrp' => $this->mrp,
                'offer_price' => $this->offer_price,
                'saving_percent' => $this->saving_percent,
                'saving_amount' => $this->saving_amount,
                'features_editor' => $this->features_editor,
            ]
        );
    }
}
