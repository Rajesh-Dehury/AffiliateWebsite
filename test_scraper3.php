<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpClient\HttpClient;

$client = HttpClient::create();

// Test: try homepage which works
$response = $client->request('GET', 'https://www.indiafreestuff.in/', [
    'headers' => [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
        'Accept-Language' => 'en-US,en;q=0.9',
    ]
]);

echo 'Homepage Status: ' . $response->getStatusCode() . PHP_EOL;
$body = $response->getContent(false);
echo 'Homepage length: ' . strlen($body) . PHP_EOL;

// Parse with crawler
$crawler = new Symfony\Component\DomCrawler\Crawler($body);
echo 'Homepage .product-item: ' . $crawler->filter('.product-item')->count() . PHP_EOL;
echo 'Homepage #superdeals: ' . $crawler->filter('#superdeals')->count() . PHP_EOL;
echo 'Homepage #trending: ' . $crawler->filter('#trending')->count() . PHP_EOL;

// Check content of superdeals or trending
if ($crawler->filter('#superdeals')->count() > 0) {
    $html = $crawler->filter('#superdeals')->html();
    echo 'superdeals html length: ' . strlen($html) . PHP_EOL;
    $subCrawler = new Symfony\Component\DomCrawler\Crawler($html);
    echo 'superdeals .product-item: ' . $subCrawler->filter('.product-item')->count() . PHP_EOL;
}

if ($crawler->filter('#trending')->count() > 0) {
    $html = $crawler->filter('#trending')->html();
    echo 'trending html length: ' . strlen($html) . PHP_EOL;
    $subCrawler = new Symfony\Component\DomCrawler\Crawler($html);
    echo 'trending .product-item: ' . $subCrawler->filter('.product-item')->count() . PHP_EOL;
}

// Also check for product-list which we saw in the webfetch content
echo 'Homepage .product-list: ' . $crawler->filter('.product-list')->count() . PHP_EOL;

// Try to get content even on error for trending
echo PHP_EOL . '--- Trending page ---' . PHP_EOL;
try {
    $response2 = $client->request('GET', 'https://www.indiafreestuff.in/deals/trending', [
        'headers' => [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
            'Accept-Language' => 'en-US,en;q=0.9',
        ]
    ]);
    echo 'Status: ' . $response2->getStatusCode() . PHP_EOL;
    $body2 = $response2->getContent(false);
    echo 'Length: ' . strlen($body2) . PHP_EOL;
    $crawler2 = new Symfony\Component\DomCrawler\Crawler($body2);
    echo '.product-item: ' . $crawler2->filter('.product-item')->count() . PHP_EOL;
    if (strpos($body2, 'cf-browser-verify') !== false) echo 'Has Cloudflare challenge' . PHP_EOL;
    if (strpos($body2, '__cf_challenge') !== false) echo 'Has __cf_challenge' . PHP_EOL;
} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
    // Try to get content anyway
    if (method_exists($e, 'getResponse')) {
        $resp = $e->getResponse();
        echo 'Status from exception: ' . $resp->getStatusCode() . PHP_EOL;
        $body2 = $resp->getContent(false);
        echo 'Length: ' . strlen($body2) . PHP_EOL;
        file_put_contents(__DIR__ . '/trending_403.html', $body2);
        echo 'Saved to trending_403.html' . PHP_EOL;
    }
}
