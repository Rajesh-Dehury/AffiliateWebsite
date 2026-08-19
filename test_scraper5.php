<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;

$client = HttpClient::create();

// Step 1: Get homepage to establish cookies
$response = $client->request('GET', 'https://www.indiafreestuff.in/', [
    'headers' => [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
        'Accept-Language' => 'en-US,en;q=0.9',
        'Accept-Encoding' => 'gzip',
    ]
]);

$homeBody = $response->getContent(false);
$crawler = new Crawler($homeBody);

// Look for the AJAX endpoint
$scripts = $crawler->filter('script');
$scripts->each(function($node) {
    $text = $node->text();
    if (strpos($text, 'getdeals') !== false) {
        echo "Found getdeals reference" . PHP_EOL;
    }
});

// Extract cookies
$cookies = '';
foreach ($response->getHeaders() as $name => $values) {
    if (strtolower($name) === 'set-cookie') {
        foreach ($values as $v) {
            $parts = explode(';', $v);
            $cookies .= $parts[0] . '; ';
        }
    }
}
echo 'Cookies: ' . ($cookies ?: '(none)') . PHP_EOL;

// Check if JS code reveals the actual endpoint
// Look for the dealdata element
echo 'Has #dealdata: ' . $crawler->filter('#dealdata')->count() . PHP_EOL;

// Step 2: Call the AJAX endpoint
echo PHP_EOL . '--- Fetching getdeals endpoint ---' . PHP_EOL;
try {
    $response2 = $client->request('GET', 'https://www.indiafreestuff.in/pages/getdeals', [
        'headers' => [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
            'Accept' => 'text/html, */*; q=0.01',
            'Accept-Language' => 'en-US,en;q=0.9',
            'X-Requested-With' => 'XMLHttpRequest',
            'Referer' => 'https://www.indiafreestuff.in/',
            'Cookie' => rtrim($cookies, '; '),
        ]
    ]);

    echo 'getdeals Status: ' . $response2->getStatusCode() . PHP_EOL;
    $dealsBody = $response2->getContent(false);
    echo 'getdeals length: ' . strlen($dealsBody) . PHP_EOL;

    $dealsCrawler = new Crawler($dealsBody);
    $items = $dealsCrawler->filter('.product-item');
    echo '.product-item count: ' . $items->count() . PHP_EOL;

    if ($items->count() > 0) {
        $items->each(function($node, $i) {
            if ($i >= 3) return;
            echo PHP_EOL . "--- Deal #$i ---" . PHP_EOL;

            $title = '';
            try { $title = trim($node->filter('a.item-title')->text()); } catch (\Exception $e) {}
            echo 'Title: ' . $title . PHP_EOL;

            $price = '';
            try { $price = trim($node->filter('.new-price p')->text()); } catch (\Exception $e) {}
            echo 'Price: ' . $price . PHP_EOL;

            $mrp = '';
            try { $mrp = trim($node->filter('.old-price p')->text()); } catch (\Exception $e) {}
            echo 'MRP: ' . $mrp . PHP_EOL;

            $discount = '';
            try { $discount = trim($node->filter('.off-discount p')->text()); } catch (\Exception $e) {}
            echo 'Discount: ' . $discount . PHP_EOL;

            $shopNow = '';
            try { $shopNow = $node->filter('a.btn-shopnow')->attr('href'); } catch (\Exception $e) {}
            echo 'Shop Now URL: ' . $shopNow . PHP_EOL;

            $image = '';
            try { $image = $node->filter('.product-img img.lazy')->attr('data-original'); } catch (\Exception $e) {}
            echo 'Image: ' . $image . PHP_EOL;

            $isAmazon = false;
            try {
                $storeLink = $node->filter('a.barnd-logo-small')->first();
                if ($storeLink->count() > 0) {
                    echo 'Store href: ' . $storeLink->attr('href') . PHP_EOL;
                    if (strpos($storeLink->attr('href'), '/stores/amazon') !== false) {
                        $isAmazon = true;
                    }
                }
            } catch (\Exception $e) {}
            echo 'Is Amazon: ' . ($isAmazon ? 'YES' : 'NO') . PHP_EOL;
        });
    }

    // Also try the trending endpoint via AJAX
    echo PHP_EOL . '--- Testing /deals/trending with XHR headers ---' . PHP_EOL;
    try {
        $response3 = $client->request('GET', 'https://www.indiafreestuff.in/deals/trending', [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Accept' => 'text/html, */*; q=0.01',
                'X-Requested-With' => 'XMLHttpRequest',
                'Referer' => 'https://www.indiafreestuff.in/',
                'Cookie' => rtrim($cookies, '; '),
            ]
        ]);
        echo 'trending XHR Status: ' . $response3->getStatusCode() . PHP_EOL;
        $trendBody = $response3->getContent(false);
        echo 'trending length: ' . strlen($trendBody) . PHP_EOL;
        $trendCrawler = new Crawler($trendBody);
        echo 'trending .product-item: ' . $trendCrawler->filter('.product-item')->count() . PHP_EOL;
    } catch (\Exception $e) {
        echo 'trending XHR Error: ' . $e->getMessage() . PHP_EOL;
    }

} catch (\Exception $e) {
    echo 'getdeals Error: ' . $e->getMessage() . PHP_EOL;
}
