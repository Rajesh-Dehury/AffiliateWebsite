<?php

namespace App\Livewire;

use App\Models\AmazonDeals;
use Livewire\Component;

class PostDetails extends Component
{
    public $prod_id;
    public $record;

    public function mount($prod_id)
    {
        $this->prod_id = $prod_id;
        $this->record = AmazonDeals::find($prod_id);
    }

    public function render()
    {
        return view('livewire.post-details', [
            'record' => $this->record
        ])->layout('components.home-layout');
    }
}
