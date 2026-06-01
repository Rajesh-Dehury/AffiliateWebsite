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
    public $telegramMessage;

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

    public function postToTelegram()
    {
        $this->validate([
            'telegramMessage' => 'required|min:5',
        ]);

        try {
            \Telegram\Bot\Laravel\Facades\Telegram::sendMessage([
                'chat_id' => config('services.telegram.chat_id'),
                'text' => $this->telegramMessage,
                'parse_mode' => 'HTML',
            ]);

            $this->telegramMessage = '';
            session()->flash('success', 'Message posted to Telegram successfully!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Telegram post error: ' . $e->getMessage());
            session()->flash('error', 'Failed to post to Telegram: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.dashboard')->layout('components.admin-layout');
    }
}
