<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Symfony\Component\BrowserKit\HttpBrowser;

class UrlGenerate extends Component
{
    public $url;
    public $asin;
    public $amazon_partner_tag;
    public $new_url;

    protected $rules = [
        'url' => 'required|url',
    ];

    public function mount()
    {
        $this->amazon_partner_tag = env('AMAZON_PARTNER_TAG');
    }

    public function scrape()
    {
        $this->validate();

        // Parse the URL to check for 'pd_rd_i' parameter
        $parsed_url = parse_url($this->url);
        parse_str($parsed_url['query'] ?? '', $query_params);

        if (isset($query_params['pd_rd_i'])) {
            // If 'pd_rd_i' parameter is present, use it as the ASIN
            $this->asin = $query_params['pd_rd_i'];
        } else {
            // Otherwise, scrape the ASIN from the page
            $client = new HttpBrowser();
            $crawler = $client->request('GET', $this->url);

            $this->asin = $crawler->filter('input[name="ASIN"]')->attr('value');
        }

        // Generate the new URL with the ASIN and partner tag
        $this->new_url = "https://www.amazon.in/dp/{$this->asin}?psc=1&th=1&tag={$this->amazon_partner_tag}";
    }

    public function render()
    {
        return view('livewire.admin.url-generate')->layout('components.admin-layout');
    }
}
