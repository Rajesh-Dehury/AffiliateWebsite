<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpClient\HttpClient;

$client = HttpClient::create();
$response = $client->request('GET', 'https://www.indiafreestuff.in/', [
    'headers' => [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
        'Accept-Language' => 'en-US,en;q=0.9',
    ]
]);

$body = $response->getContent(false);
$crawler = new Symfony\Component\DomCrawler\Crawler($body);

// Check product-list HTML
$crawler->filter('.product-list')->each(function($node, $i) {
    echo "product-list #$i html length: " . strlen($node->html()) . PHP_EOL;
    echo substr($node->html(), 0, 1000) . PHP_EOL;
    echo '---' . PHP_EOL;
});

// Also search for any script that loads deals
$scripts = $crawler->filter('script');
$scripts->each(function($node) {
    $text = $node->text();
    if (strpos($text, 'load') !== false || strpos($text, 'ajax') !== false || strpos($text, 'pagination') !== false) {
        if (strlen($text) > 50 && strlen($text) < 5000) {
            echo 'Found script with load/ajax: ' . PHP_EOL . substr($text, 0, 2000) . PHP_EOL . '---' . PHP_EOL;
        }
    }
});
