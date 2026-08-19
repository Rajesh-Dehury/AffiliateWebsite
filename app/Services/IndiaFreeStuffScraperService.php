<?php

namespace App\Services;

use Symfony\Component\Process\Process;
use App\Services\AmazonApiService;
use Exception;
use Illuminate\Support\Facades\Log;

class IndiaFreeStuffScraperService
{
    protected $amazonApi;
    protected $nodeScriptPath;

    public function __construct(AmazonApiService $amazonApi)
    {
        $this->amazonApi = $amazonApi;
        $this->nodeScriptPath = base_path('resources/js/indiafreestuff-scraper.cjs');
    }

    public function scrape(int $limit = 20): array
    {
        $deals = [];

        try {
            $process = new Process(['node', $this->nodeScriptPath]);
            $process->setTimeout(60);
            $process->run();

            if (!$process->isSuccessful()) {
                Log::error('IndiaFreeStuff Node Script Error: ' . $process->getErrorOutput());
                return [];
            }

            $output = $process->getOutput();
            $rawDeals = json_decode($output, true);

            if (!is_array($rawDeals)) {
                Log::error('IndiaFreeStuff: Invalid JSON from Node script');
                return [];
            }

            foreach ($rawDeals as $deal) {
                if (count($deals) >= $limit) break;

                if (empty($deal['title']) || empty($deal['deal_url'])) continue;

                $asin = $this->resolveAsinFromDealUrl($deal['deal_url']);

                $deals[] = [
                    'title' => $deal['title'],
                    'offer_price' => $deal['offer_price'] ?? 0,
                    'mrp' => $deal['mrp'] ?? 0,
                    'discount' => $deal['discount'] ?? 0,
                    'deal_url' => $deal['deal_url'],
                    'image_url' => $deal['image_url'] ?? '',
                    'asin' => $asin
                ];
            }
        } catch (Exception $e) {
            Log::error('IndiaFreeStuff Scraper Error: ' . $e->getMessage());
        }

        return $deals;
    }

    protected function resolveAsinFromDealUrl(string $url): ?string
    {
        $parsedUrl = parse_url($url);
        parse_str($parsedUrl['query'] ?? '', $queryParams);

        if (!isset($queryParams['rto'])) return null;

        $decoded = base64_decode($queryParams['rto'], true);
        if ($decoded === false) return null;

        if (is_numeric($decoded)) {
            return null;
        }

        if (strpos($decoded, 'http') === 0 || strpos($decoded, 'amazon') !== false) {
            return $this->extractAsinFromAmazonUrl($decoded);
        }

        $fullUrl = 'http' . (strpos($decoded, '://') !== false ? '' : 's://') . ltrim($decoded, '/');
        if (filter_var($fullUrl, FILTER_VALIDATE_URL)) {
            return $this->extractAsinFromAmazonUrl($fullUrl);
        }

        return null;
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
