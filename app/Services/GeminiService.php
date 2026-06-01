<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private string $apiKey;
    private string $model;
    private string $affiliateTag;

    public function __construct()
    {
        $this->apiKey       = (string) config('services.gemini.api_key');
        $this->model        = (string) config('services.gemini.model', 'gemini-2.0-flash');
        $this->affiliateTag = (string) config('services.gemini.affiliate_tag', 'codewithrd-21');
    }

    public function fetchDeals(array $filters): array
    {
        $prompt = $this->buildPrompt($filters);

        $response = Http::timeout(60)->post(
            "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}",
            [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.1,
                    'response_mime_type' => 'application/json',
                ]
            ]
        );

        if ($response->failed()) {
            Log::error('Gemini API error', ['response' => $response->body()]);
            throw new \Exception('Failed to fetch deals from AI. Please try again.');
        }

        $data = $response->json();
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

        if (empty($text)) {
            throw new \Exception('Empty response from AI.');
        }

        $deals = json_decode($text, true);

        if (!is_array($deals)) {
            Log::error('Invalid JSON from Gemini', ['text' => $text]);
            throw new \Exception('Invalid response format from AI.');
        }

        // Append affiliate tag to each deal
        return array_map(function ($deal) {
            $asin = $deal['asin'] ?? '';
            $deal['affiliate_link'] = "https://www.amazon.in/dp/{$asin}?tag={$this->affiliateTag}";
            return $deal;
        }, $deals);
    }

    private function buildPrompt(array $filters): string
    {
        $category    = $filters['category'] ?? 'all';
        $brands      = $filters['brands'] ?? 'any';
        $minDiscount = $filters['min_discount'] ?? 50;
        $minRating   = $filters['min_rating'] ?? 4.0;
        $priceRange  = $filters['price_range'] ?? 'any';
        $audience    = $filters['audience'] ?? 'general buyers in India';
        $primeOnly   = ($filters['prime_only'] ?? false) ? 'YES' : 'No preference';
        $sortBy      = $filters['sort_by'] ?? 'relevance';

        $categoryLabel = $category === 'all'
            ? 'best mix of Electronics, Smartphones, Laptops, Kitchen, Home, Fashion'
            : $category;

        return <<<PROMPT
You are an expert Amazon India affiliate marketer. Generate exactly 10 REAL, highly popular Amazon India product deals.

CRITICAL: ONLY use REAL, VALID ASINs from this verified list of top-sellers IF THEY MATCH THE FILTERS. If the filters (like Brand or Category) are specific, find the closest real ASINs you know for those specific criteria.

VERIFIED ASIN EXAMPLES (Use these or other REAL known ASINs):
1. B0CHX1W1XY - Apple iPhone 15
2. B0D7B6X9X9 - Samsung Galaxy M35 5G
3. B09N3ZLB3T - boAt Airdopes 141
4. B0B3C5S7S1 - MacBook Air M2
5. B08697N43N - Butterfly Chopper
6. B08HVL8QN3 - Mi Power Bank
7. B0B8S6V6S2 - Samsung Buds2 Pro
8. B0BDYVC5TD - SanDisk 128GB
9. B01DEWVZ2C - JBL C100SI
10. B0BYM3X7S3 - Sony WH-CH520

Filters for this request:
- Category: {$categoryLabel}
- Specific Brands: {$brands}
- Minimum Discount: {$minDiscount}% off
- Minimum Rating: {$minRating} stars or higher
- Price Range: ₹{$priceRange}
- Target Audience: {$audience}
- Prime Only: {$primeOnly}
- Sort By: {$sortBy}

Requirements:
- Ensure 'currentPrice' is exactly 'originalPrice' minus the discount.
- All 'asin' values MUST be real 10-character Amazon India IDs.
- 'isPrime' should reflect the Prime Only filter.

Return ONLY a raw JSON array with exactly 10 objects. No markdown.

JSON structure:
{"rank":1,"name":"Brand + Model + Specs","category":"Category","asin":"B0XXXXXXXX","currentPrice":1299,"originalPrice":2999,"discountPercent":56,"isPrime":true,"rating":4.2,"reviewCount":"45,000","emoji":"📦","imageUrl":"https://m.media-amazon.com/images/I/example.jpg"}
PROMPT;
    }

}
