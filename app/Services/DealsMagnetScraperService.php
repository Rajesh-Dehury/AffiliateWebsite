<?php

namespace App\Services;

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use App\Services\AmazonApiService;
use Exception;

class DealsMagnetScraperService
{
    protected $browser;
    protected $amazonApi;
    protected $baseUrl = 'https://www.dealsmagnet.com';

    public function __construct(AmazonApiService $amazonApi)
    {
        $this->amazonApi = $amazonApi;
        $this->browser = new HttpBrowser(HttpClient::create());
        $this->browser->setServerParameters([
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
        ]);
    }

    public function scrape(int $limit = 20): array
    {
        $deals = [];
        $url = $this->baseUrl . '/new';

        try {
            $crawler = $this->browser->request('GET', $url);
            
            $crawler->filter('.card')->each(function ($node) use (&$deals, $limit) {
                if (count($deals) >= $limit) return;

                try {
                    // 1. Check if it's an Amazon deal (optional but good for consistency)
                    $isAmazon = false;
                    try {
                        $storeImg = $node->filter('.card-footer img')->first();
                        if ($storeImg->count() > 0 && strpos(strtolower($storeImg->attr('alt')), 'amazon') !== false) {
                            $isAmazon = true;
                        }
                    } catch (\Exception $e) {}
                    
                    if (!$isAmazon) return;

                    // 2. Extract Title
                    $titleNode = $node->filter('.card-body .MainCardAnchore')->first();
                    if ($titleNode->count() === 0) return;
                    $title = trim($titleNode->text());
                    
                    // 3. Extract Deal Link
                    $dealUrl = $this->baseUrl . $titleNode->attr('href');
                    if (strpos($titleNode->attr('href'), 'http') === 0) {
                        $dealUrl = $titleNode->attr('href');
                    }
                    
                    // 4. Extract Prices
                    $offerPriceText = '';
                    try {
                        $offerPriceText = $node->filter('.card-DealPrice')->text();
                    } catch (\Exception $e) {}
                    
                    $mrpText = '';
                    try {
                        $mrpText = $node->filter('.card-OriginalPrice')->text();
                    } catch (\Exception $e) {}
                    
                    $offerPrice = (int)preg_replace('/[^0-9]/', '', $offerPriceText);
                    $mrp = (int)preg_replace('/[^0-9]/', '', $mrpText);
                    if ($mrp === 0) $mrp = $offerPrice;
                    
                    // 5. Discount
                    $discount = 0;
                    try {
                        $discountText = $node->filter('.card-DiscountPrice .big')->text();
                        $discount = (int)preg_replace('/[^0-9]/', '', $discountText);
                    } catch (\Exception $e) {}

                    // 6. Image
                    $imageUrl = '';
                    try {
                        $imgNode = $node->filter('.card-img img')->first();
                        $imageUrl = $imgNode->attr('data-src') ?? $imgNode->attr('src') ?? '';
                        if ($imageUrl && strpos($imageUrl, 'http') !== 0) {
                            $imageUrl = $this->baseUrl . $imageUrl;
                        }
                    } catch (\Exception $e) {}

                    if ($offerPrice > 0) {
                        $deals[] = [
                            'title' => $title,
                            'offer_price' => $offerPrice,
                            'mrp' => $mrp,
                            'discount' => $discount,
                            'deal_url' => $dealUrl,
                            'image_url' => $imageUrl,
                            'asin' => null
                        ];
                    }
                } catch (\Exception $e) {
                    Log::error('DealsMagnet Node Parse Error: ' . $e->getMessage());
                }
            });

            // If we have deals but no ASINs, we need to visit deal pages
            foreach ($deals as &$deal) {
                if (!$deal['asin']) {
                    $deal['asin'] = $this->fetchAsinFromDealPage($deal['deal_url']);
                    usleep(300000); // 0.3s delay
                }
            }

        } catch (Exception $e) {
            Log::error('DealsMagnet Scraper Error: ' . $e->getMessage());
        }

        return array_slice($deals, 0, $limit);
    }

    protected function fetchAsinFromDealPage(string $url): ?string
    {
        try {
            $crawler = $this->browser->request('GET', $url);
            
            // Look for the buy button which contains the redirect data-code
            $buyButton = $crawler->filter('.buy-button')->first();
            
            if ($buyButton->count() > 0) {
                $dataCode = $buyButton->attr('data-code');
                $redirectUrl = "https://www.dealsmagnet.com/buy?" . $dataCode;
                
                // We need to follow the redirect to get the final Amazon URL
                // Symfony HttpBrowser follows redirects by default, but we just want the Location header if possible
                // to save time, or we just let it follow and check the final URI.
                $this->browser->request('GET', $redirectUrl);
                $finalUrl = $this->browser->getHistory()->current()->getUri();
                
                if (strpos($finalUrl, 'amazon.in') !== false) {
                     return $this->extractAsinFromAmazonUrl($finalUrl);
                }
            }
            
            // Fallback: Check for any links if button failed
            $buyLink = $crawler->filter('a:contains("Buy"), a:contains("Grab")')->first();
            if ($buyLink->count() > 0) {
                return $this->extractAsinFromAmazonUrl($buyLink->attr('href'));
            }
            
        } catch (\Exception $e) {
            Log::error('ASIN Fetch Error: ' . $e->getMessage() . ' for URL: ' . $url);
        }
        return null;
    }

    protected function formatProductDetails(array $item): array
    {
        $title = $item['ItemInfo']['Title']['DisplayValue'] ?? '';
        $primaryImage = $item['Images']['Primary']['Large']['URL'] ?? '';
        $price = $item['Offers']['Listings'][0]['Price']['Amount'] ?? 0;
        $mrp = $item['Offers']['Listings'][0]['SavingBasis']['Amount'] ?? $price;
        
        return [
            'title' => $title,
            'detail_page_url' => $item['DetailPageURL'] ?? '',
            'primary_large_url' => $primaryImage,
            'price' => $price,
            'mrp' => $mrp,
            'features' => ''
        ];
    }

    protected function extractAsinFromAmazonUrl(string $amazonUrl): ?string
    {
        if (preg_match('/\/(?:dp|gp\/product)\/([A-Z0-9]{10})/', $amazonUrl, $matches)) {
            return $matches[1];
        }
        
        $parsedUrl = parse_url($amazonUrl);
        parse_str($parsedUrl['query'] ?? '', $queryParams);
        if (isset($queryParams['ASIN']) && preg_match('/^[A-Z0-9]{10}$/', $queryParams['ASIN'])) {
            return $queryParams['ASIN'];
        }
        
        return null;
    }
}
