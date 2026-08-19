<?php
require __DIR__ . '/vendor/autoload.php';

$client = Symfony\Component\HttpClient\HttpClient::create();
$response = $client->request('GET', 'https://www.indiafreestuff.in/', [
    'headers' => [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
    ]
]);
$body = $response->getContent(false);

// Search for any JSON-encoded deal data
$patterns = [
    '/{"products?".*?}/is',
    '/\[{"product.*?}\]/is',
    '/"title".*?"price"/is',
    '/"offer_price"/',
    '/"mrp"/',
    '/"deal_url"/',
    '/"image_url"/',
    '/"asin"/',
    '/data-deal/',
    '/data-product/',
    '/window\.__INITIAL_STATE__/',
    '/window\.__DATA__/',
    '/wp-json/',
    '/rest-api/',
];

foreach ($patterns as $p) {
    if (preg_match($p, $body, $m)) {
        echo "Found pattern: $p" . PHP_EOL;
        echo "Match: " . substr($m[0], 0, 200) . PHP_EOL . PHP_EOL;
    }
}

// Check for #dealdata or similar container
$crawler = new Symfony\Component\DomCrawler\Crawler($body);
echo PHP_EOL . 'Searching for data containers...' . PHP_EOL;
foreach (['#dealdata', '#product-list-data', '.deal-data', '#product_data', '#json-deals', 'script[type="application/json"]'] as $sel) {
    $count = $crawler->filter($sel)->count();
    if ($count > 0) echo "Found $sel: $count" . PHP_EOL;
}

// Check script tags for JSON
$scripts = $crawler->filter('script');
echo PHP_EOL . 'Script tags: ' . $scripts->count() . PHP_EOL;
$scripts->each(function($node) {
    $text = trim($node->text());
    if (strlen($text) > 100 && strlen($text) < 50000) {
        if (preg_match('/product|deal|amazon|price/i', $text)) {
            echo "Script length: " . strlen($text) . ", contains deal data" . PHP_EOL;
            echo substr($text, 0, 300) . PHP_EOL . '---' . PHP_EOL;
        }
    }
});

// Check for any data attributes in HTML
$html = $body;
if (preg_match_all('/data-[a-z]+="[^"]*product[^"]*"/i', $html, $m)) {
    echo PHP_EOL . 'Found data-product attributes: ' . count($m[0]) . PHP_EOL;
}
