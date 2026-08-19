<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpClient\HttpClient;

$client = HttpClient::create();

$response = $client->request('GET', 'https://www.indiafreestuff.in/deals/trending', [
    'headers' => [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
    ]
]);

$body = $response->getContent(false);
echo 'Status: ' . $response->getStatusCode() . PHP_EOL;
echo 'Headers:' . PHP_EOL;
foreach ($response->getHeaders(false) as $name => $values) {
    echo "  $name: " . implode(', ', $values) . PHP_EOL;
}
echo PHP_EOL . 'Body:' . PHP_EOL;
echo $body . PHP_EOL;
