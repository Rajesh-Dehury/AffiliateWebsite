<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Subscriptions extends Component
{
    use WithPagination;

    public function deleteSubscription($id)
    {
        DB::table('subscriptions')->where('id', $id)->delete();
        session()->flash('message', 'Subscription deleted successfully.');
    }

    public function render()
    {
        $subscriptions = DB::table('subscriptions')->orderBy('created_at', 'desc')->paginate(10);
        return view('livewire.admin.subscriptions', [
            'subscriptions' => $subscriptions
        ])->layout('components.admin-layout');
    }
}
