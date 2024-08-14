<?php

namespace App\Livewire;

use App\Models\Contact;
use Livewire\Component;

class ContactUs extends Component
{
    public $email;
    public $subject;
    public $message;

    protected $rules = [
        'email' => 'required|email',
        'subject' => 'required|string',
        'message' => 'required|string',
    ];

    public function submit()
    {
        $this->validate();

        // Store data in the database
        Contact::create([
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
        ]);

        // Optionally, reset form fields
        $this->reset(['email', 'subject', 'message']);

        session()->flash('success', 'Your message has been sent successfully!');
    }

    public function render()
    {
        return view('livewire.contact-us')->layout('components.home-layout');
    }
}
