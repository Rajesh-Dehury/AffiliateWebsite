<?php
require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$scraper = new App\Services\IndiaFreeStuffScraperService(new App\Services\AmazonApiService());
$deals = $scraper->scrape(5);

echo 'Deals found: ' . count($deals) . PHP_EOL;
foreach ($deals as $d) {
    echo ' - ' . mb_substr($d['title'], 0, 60) . '... | Price: ' . $d['offer_price'] . ' | ASIN: ' . ($d['asin'] ?? 'null') . PHP_EOL;
}
