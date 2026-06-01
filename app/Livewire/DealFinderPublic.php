<?php

namespace App\Livewire;

use Livewire\Component;

class DealFinderPublic extends Component
{
    public string $keyword = '';
    public string $category = 'all';
    public int $minDiscount = 20;
    public int $maxDiscount = 80;
    public string $minPrice = '';
    public string $maxPrice = '';
    public string $sortBy = 'relevance';

    public function redirectToAmazon(): void
    {
        $url = $this->buildAmazonUrl();
        $this->js("window.open('{$url}', '_blank');");
    }

    private function buildAmazonUrl(): string
    {
        $params = [];

        if ($this->keyword) {
            $params['k'] = str_replace(' ', '+', $this->keyword);
        }

        // Category mapping for Amazon `i` param
        $categoryMap = [
            'all' => '',
            'Baby Products' => 'baby',
            'Beauty & Skin Care Products' => 'beauty',
            'Books' => 'books',
            'Computers & Accessories' => 'computers',
            'Electronics' => 'electronics',
            'Fashion' => 'fashion',
            'Garden & Outdoor Living' => 'lawngarden',
            'Grocery & Gourmet Foods' => 'grocery',
            'Health Household & Personal Care' => 'hpc',
            'Home & Kitchen' => 'kitchen',
            'Luxury Beauty' => 'beauty',
            'Men' => 'fashion',
            'Movies & TV Shows' => 'movies-tv',
            'Music' => 'music',
            'Musical Instruments' => 'musical-instruments',
            'Pet Supplies' => 'pets',
            'Software' => 'software',
            'Sports, Fitness & Outdoors' => 'sports',
            'Stationery & Office Products' => 'office-products',
            'Toys & Games' => 'toys',
            'Vehicle Parts & Accessories' => 'automotive',
        ];

        if ($this->category !== 'all' && isset($categoryMap[$this->category])) {
            $params['i'] = $categoryMap[$this->category];
        }

        // Price range
        if ($this->minPrice) {
            $params['low-price'] = $this->minPrice;
        }
        if ($this->maxPrice) {
            $params['high-price'] = $this->maxPrice;
        }

        // Discount filter (pct-off)
        if ($this->minDiscount > 0 || $this->maxDiscount < 100) {
            $params['pct-off'] = $this->minDiscount . '-' . $this->maxDiscount;
        }

        // Sort mapping
        $sortMap = [
            'relevance' => 'relevance',
            'rating' => 'review-rank',
            'price_low' => 'price-asc-rank',
            'price_high' => 'price-desc-rank',
            'newest' => 'date-desc-rank',
        ];
        if (isset($sortMap[$this->sortBy])) {
            $params['s'] = $sortMap[$this->sortBy];
        }

        // Affiliate tag
        $params['tag'] = 'codewithrd-21';

        return 'https://www.amazon.in/s?' . http_build_query($params);
    }

    public function render()
    {
        return view('livewire.deal-finder-public');
    }
}
