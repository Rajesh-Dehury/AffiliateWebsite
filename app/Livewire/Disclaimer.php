<?php

namespace App\Livewire;

use Livewire\Component;

class Disclaimer extends Component
{
    public function render()
    {
        return view('livewire.disclaimer')->layout('components.home-layout');
    }
}
