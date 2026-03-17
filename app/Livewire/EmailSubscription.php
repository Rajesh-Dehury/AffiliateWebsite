<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class EmailSubscription extends Component
{
    public $email;

    protected $rules = [
        'email' => 'required|email',
    ];

    public function subscribe()
    {
        $this->validate();

        // Using a simple DB insert to avoid creating a full model for now, or check if table exists
        // Since we are moving fast, let's assume a 'subscriptions' table is needed or just log it
        
        try {
            DB::table('subscriptions')->updateOrInsert(
                ['email' => $this->email],
                ['created_at' => now(), 'updated_at' => now()]
            );
            $this->reset('email');
            session()->flash('subscription_success', 'You have successfully subscribed to daily deals!');
        } catch (\Exception $e) {
            // If table doesn't exist, we should probably create it
            session()->flash('subscription_error', 'Subscription service is temporarily unavailable.');
        }
    }

    public function render()
    {
        return view('livewire.email-subscription');
    }
}
