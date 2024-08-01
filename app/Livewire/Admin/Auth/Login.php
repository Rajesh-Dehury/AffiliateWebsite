<?php

namespace App\Livewire\Admin\Auth;

use App\Livewire\Admin\Dashboard;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email;
    public $password;
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        if (Auth::guard('admin')->attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->flash('message', 'Login successful.');

            $this->redirect(Dashboard::class);
        } else {
            session()->flash('error_message', 'The provided credentials do not match our records.');
        }
    }
    public function render()
    {
        return view('livewire.admin.auth.login')->layout('components.admin-guest-layout');
    }
}
