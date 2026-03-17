<?php

namespace App\Livewire;

use App\Models\AmazonDeals;
use Livewire\Component;

class PostDetails extends Component
{
    public $prod_id;
    public $record;
    public $record_latests;

    public function mount($slug_or_id)
    {
        // Find by slug or ID
        $this->record = AmazonDeals::where('slug', $slug_or_id)->orWhere('id', $slug_or_id)->firstOrFail();
        
        // Update prod_id for backward compatibility with latest deals query
        $this->prod_id = $this->record->id;
        
        // Increment the view count
        $this->record->increment('views_count');
        
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
