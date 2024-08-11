<?php

namespace App\Livewire\Admin;

use App\Models\AmazonDeals;
use Livewire\Component;

class PostDetails extends Component
{
    public $postDetail;

    public $product_asin;
    public $detail_page_url;
    public $primary_large_url;
    public $product_title;
    public $mrp;
    public $offer_price;
    public $saving_percent;
    public $saving_amount;
    public $features_editor;
    public $our_link;
    public $wp_post;
    public $our_post;

    public function mount($id)
    {
        $this->postDetail = AmazonDeals::find($id);

        $this->product_asin = $this->postDetail->product_asin;
        $this->detail_page_url = $this->postDetail->detail_page_url;
        $this->primary_large_url = $this->postDetail->primary_large_url;
        $this->product_title = $this->postDetail->product_title;
        $this->mrp = $this->postDetail->mrp;
        $this->offer_price = $this->postDetail->offer_price;
        $this->saving_percent = $this->postDetail->saving_percent;
        $this->saving_amount = $this->postDetail->saving_amount;
        $this->features_editor = $this->postDetail->features_editor;
        $this->our_link = $this->postDetail->our_link;
        $this->wp_post = $this->postDetail->wp_post;
        $this->our_post = $this->postDetail->our_post;
    }
    public function render()
    {
        return view('livewire.admin.post-details')->layout('components.admin-layout');
    }
}
