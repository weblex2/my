<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Quote;
use App\Mail\QuoteEmail;
use Illuminate\Support\Facades\Mail;

class SendQuoteEmail extends Component
{
    public $quoteId;
    public $recipient_email;
    public $subject = 'Ihr Angebot';
    public $message = '';
    public $open = false;

    protected $listeners = ['openModal' => 'openModal'];

    // Empfängt das quoteId direkt als Parameter
    public function openModal($quoteId)
    {
        $this->quoteId = $quoteId;
        $this->open = true;
    }

    public function sendEmail()
    {
        $quote = Quote::find($this->quoteId);

        Mail::to($this->recipient_email)
            ->send(new QuoteEmail($quote, $this->subject, $this->message));

        session()->flash('message', 'E-Mail erfolgreich gesendet!');
        $this->dispatch('closeModal'); // Verwende $this->dispatch in Livewire v3
    }

    public function render()
    {
        return view('livewire.send-quote-email');
    }
}
