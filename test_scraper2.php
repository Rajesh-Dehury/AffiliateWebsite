<?php
require __DIR__ . '/vendor/autoload.php';

// First get the homepage to get cookies
$client = Symfony\Component\HttpClient\HttpClient::create();
$response = $client->request('GET', 'https://www.indiafreestuff.in/', [
    'headers' => [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
        'Accept-Language' => 'en-US,en;q=0.9',
        'Accept-Encoding' => 'gzip, deflate, br',
        'Connection' => 'keep-alive',
        'Upgrade-Insecure-Requests' => '1',
        'Sec-Fetch-Dest' => 'document',
        'Sec-Fetch-Mode' => 'navigate',
        'Sec-Fetch-Site' => 'none',
        'Sec-Fetch-User' => '?1',
        'Cache-Control' => 'max-age=0',
    ]
]);

echo 'Homepage Status: ' . $response->getStatusCode() . PHP_EOL;
echo 'Homepage Content length: ' . strlen($response->getContent()) . PHP_EOL;

$cookies = '';
foreach ($response->getHeaders() as $name => $values) {
    if (strtolower($name) === 'set-cookie') {
        foreach ($values as $v) {
            $parts = explode(';', $v);
            $cookies .= $parts[0] . '; ';
        }
    }
}
echo 'Cookies: ' . $cookies . PHP_EOL;

// Now try trending page with cookies
echo PHP_EOL . '--- Trying trending page ---' . PHP_EOL;
$response2 = $client->request('GET', 'https://www.indiafreestuff.in/deals/trending', [
    'headers' => [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
        'Accept-Language' => 'en-US,en;q=0.9',
        'Cookie' => rtrim($cookies, '; '),
        'Referer' => 'https://www.indiafreestuff.in/',
        'Sec-Fetch-Dest' => 'document',
        'Sec-Fetch-Mode' => 'navigate',
        'Sec-Fetch-Site' => 'same-origin',
        'Sec-Fetch-User' => '?1',
        'Cache-Control' => 'max-age=0',
    ]
]);

echo 'Trending Status: ' . $response2->getStatusCode() . PHP_EOL;
echo 'Trending Content length: ' . strlen($response2->getContent()) . PHP_EOL;
$body = $response2->getContent();
if (strpos($body, 'product-item') !== false) {
    echo 'Found product-item in response!' . PHP_EOL;
} elseif (strpos($body, 'cf-browser-verify') !== false) {
    echo 'Cloudflare challenge page' . PHP_EOL;
} elseif (strpos($body, '403') !== false) {
    echo '403 forbidden page' . PHP_EOL;
    // Print first 500 chars
    echo substr($body, 0, 500) . PHP_EOL;
} else {
    echo 'Unknown response. First 500 chars:' . PHP_EOL;
    echo substr($body, 0, 500) . PHP_EOL;
}

echo PHP_EOL . '--- Now trying with HttpBrowser ---' . PHP_EOL;
$browser = new Symfony\Component\BrowserKit\HttpBrowser($client);
$browser->setServerParameters([
    'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
]);
$crawler = $browser->request('GET', 'https://www.indiafreestuff.in/deals/trending');
echo 'HttpBrowser Status: ' . $browser->getResponse()->getStatusCode() . PHP_EOL;
echo 'HttpBrowser Content length: ' . strlen($browser->getResponse()->getContent()) . PHP_EOL;
echo 'HttpBrowser .product-item count: ' . $crawler->filter('.product-item')->count() . PHP_EOL;
