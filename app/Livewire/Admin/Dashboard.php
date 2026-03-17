<?php

namespace App\Livewire\Admin;

use App\Models\AmazonDeals;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalViews;
    public $totalClicks;
    public $totalSubscribers;
    public $topDeals;

    public function mount()
    {
        $this->totalViews = AmazonDeals::sum('views_count');
        $this->totalClicks = AmazonDeals::sum('clicks_count');
        $this->totalSubscribers = \Illuminate\Support\Facades\DB::table('subscriptions')->count();
        
        // Get top 10 most viewed deals
        $this->topDeals = AmazonDeals::orderBy('views_count', 'desc')
                                     ->take(10)
                                     ->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard')->layout('components.admin-layout');
    }
}
