<?php

namespace App\Livewire;

use App\Models\AmazonDeals;
use Livewire\Component;

class PostDetails extends Component
{
    public $prod_id;
    public $record;
    public $record_latests;

    public function mount($prod_id)
    {
        $this->prod_id = $prod_id;
        $this->record = AmazonDeals::find($prod_id);
        $this->record_latests = AmazonDeals::latest()->where('id', '!=', $this->prod_id)->take(3)->get();
    }

    public function render()
    {
        return view('livewire.post-details', [
            'record' => $this->record,
            'record_latests' => $this->record_latests,
        ])->layout('components.home-layout');
    }
}
