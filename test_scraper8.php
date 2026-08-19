<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;

// Try with full UA-CH headers that Cloudflare expects
$client = HttpClient::create();
$response = $client->request('GET', 'https://www.indiafreestuff.in/', [
    'headers' => [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'Accept-Language' => 'en-US,en;q=0.9',
        'Accept-Encoding' => 'gzip, deflate, br',
        'Sec-CH-UA' => '"Google Chrome";v="125", "Chromium";v="125", "Not.A/Brand";v="24"',
        'Sec-CH-UA-Mobile' => '?0',
        'Sec-CH-UA-Platform' => '"Windows"',
        'Sec-Fetch-Dest' => 'document',
        'Sec-Fetch-Mode' => 'navigate',
        'Sec-Fetch-Site' => 'none',
        'Sec-Fetch-User' => '?1',
        'Upgrade-Insecure-Requests' => '1',
        'Connection' => 'keep-alive',
        'Cache-Control' => 'max-age=0',
    ]
]);

$homeBody = $response->getContent(false);
echo 'Homepage Status: ' . $response->getStatusCode() . PHP_EOL;
echo 'Homepage length: ' . strlen($homeBody) . PHP_EOL;

// Now try getdeals with cookies from homepage
$cookies = '';
foreach ($response->getHeaders() as $name => $values) {
    if (strtolower($name) === 'set-cookie') {
        foreach ($values as $v) {
            $parts = explode(';', $v);
            $cookies .= $parts[0] . '; ';
        }
    }
}
echo 'Cookies: ' . ($cookies ?: 'none') . PHP_EOL;

echo PHP_EOL . '--- Fetching getdeals endpoint ---' . PHP_EOL;
try {
    $response2 = $client->request('GET', 'https://www.indiafreestuff.in/pages/getdeals', [
        'headers' => [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
            'Accept' => 'text/html, */*; q=0.01',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Sec-CH-UA' => '"Google Chrome";v="125", "Chromium";v="125", "Not.A/Brand";v="24"',
            'Sec-CH-UA-Mobile' => '?0',
            'Sec-CH-UA-Platform' => '"Windows"',
            'X-Requested-With' => 'XMLHttpRequest',
            'Referer' => 'https://www.indiafreestuff.in/',
            'Sec-Fetch-Dest' => 'empty',
            'Sec-Fetch-Mode' => 'cors',
            'Sec-Fetch-Site' => 'same-origin',
            'Cookie' => rtrim($cookies, '; '),
        ]
    ]);
    echo 'Status: ' . $response2->getStatusCode() . PHP_EOL;
    $body = $response2->getContent(false);
    echo 'Length: ' . strlen($body) . PHP_EOL;
    $crawler = new Crawler($body);
    echo '.product-item: ' . $crawler->filter('.product-item')->count() . PHP_EOL;
    if ($crawler->filter('.product-item')->count() > 0) {
        file_put_contents(__DIR__ . '/deals_debug.html', $body);
    } else {
        echo substr($body, 0, 500) . PHP_EOL;
    }
} catch (\Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
    if (method_exists($e, 'getResponse')) {
        $resp = $e->getResponse();
        echo 'Exception Status: ' . $resp->getStatusCode() . PHP_EOL;
        echo substr($resp->getContent(false), 0, 500) . PHP_EOL;
    }
}

echo PHP_EOL . '--- Trying with file_get_contents ---' . PHP_EOL;
$opts = [
    'http' => [
        'method' => 'GET',
        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36\r\nAccept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\nAccept-Language: en-US,en;q=0.5\r\n",
        'follow_location' => 1,
    ],
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ]
];
$context = stream_context_create($opts);
$result = @file_get_contents('https://www.indiafreestuff.in/deals/trending', false, $context);
if ($result === false) {
    echo 'file_get_contents failed' . PHP_EOL;
    echo 'HTTP wrapper: ' . (ini_get('allow_url_fopen') ? 'enabled' : 'disabled') . PHP_EOL;
} else {
    echo 'file_get_contents length: ' . strlen($result) . PHP_EOL;
    $c = new Crawler($result);
    echo '.product-item: ' . $c->filter('.product-item')->count() . PHP_EOL;
}
