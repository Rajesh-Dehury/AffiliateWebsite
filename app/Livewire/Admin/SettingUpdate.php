<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Component;

class SettingUpdate extends Component
{
    public $wp_link;
    public $tele_link;
    
    public function mount()
    {
        // Load the current settings into the component's properties
        $settings = Setting::first();
        $this->wp_link = $settings->wp_link;
        $this->tele_link = $settings->tele_link;
    }

    public function updateWpLink()
    {
        $settings = Setting::first();
        $settings->update(['wp_link' => $this->wp_link]);
        session()->flash('message', 'WhatsApp link updated successfully.');
    }

    public function updateTeleLink()
    {
        $settings = Setting::first();
        $settings->update(['tele_link' => $this->tele_link]);
        session()->flash('message', 'Telegram link updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.setting-update')->layout('components.admin-layout');
    }
}
