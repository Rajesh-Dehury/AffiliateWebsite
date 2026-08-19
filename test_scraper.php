<?php
require __DIR__ . '/vendor/autoload.php';

$browser = new Symfony\Component\BrowserKit\HttpBrowser(Symfony\Component\HttpClient\HttpClient::create());
$browser->setServerParameters(['HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36']);
$crawler = $browser->request('GET', 'https://www.indiafreestuff.in/deals/trending');

echo 'Status: ' . $browser->getResponse()->getStatusCode() . PHP_EOL;
echo 'Content length: ' . strlen($browser->getResponse()->getContent()) . PHP_EOL;
echo 'Product items: ' . $crawler->filter('.product-item')->count() . PHP_EOL;
echo 'Has #superdeals: ' . $crawler->filter('#superdeals')->count() . PHP_EOL;
echo 'Has .product-list: ' . $crawler->filter('.product-list')->count() . PHP_EOL;
echo 'Has .tab-content: ' . $crawler->filter('.tab-content')->count() . PHP_EOL;

$superdeals = $crawler->filter('#superdeals');
if ($superdeals->count() > 0) {
    echo 'superdeals html length: ' . strlen($superdeals->html()) . PHP_EOL;
    echo 'superdeals .product-item count: ' . $superdeals->filter('.product-item')->count() . PHP_EOL;
}

// Also check trending tab
$trending = $crawler->filter('#trending');
if ($trending->count() > 0) {
    echo 'trending html length: ' . strlen($trending->html()) . PHP_EOL;
    echo 'trending .product-item count: ' . $trending->filter('.product-item')->count() . PHP_EOL;
}

// Check what's in the tab-content
echo PHP_EOL . '--- Tab panes ---' . PHP_EOL;
$crawler->filter('.tab-pane')->each(function($node, $i) {
    echo 'Pane #' . $i . ' (id=' . ($node->attr('id') ?? 'none') . '): html length=' . strlen($node->html()) . ', .product-item=' . $node->filter('.product-item')->count() . PHP_EOL;
});
