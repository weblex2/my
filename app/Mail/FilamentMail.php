<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FilamentMail extends Mailable
{
    public $message;
    public $body;
    public $attachment;

    public function __construct($subject, $body, $attachment=null)
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->attachment = $attachment;
    }

    public function build()
    {
        $x=1;
        return $this->from('alex@noppenberger.org', 'Alex')
                    ->subject($this->subject)
                    ->view('filament.email_templates.template')
                    ->with(['message' => $this->body])
                    //->attachData($this->attachment->output(), 'angebot.pdf', [
                    //    'mime' => 'application/pdf',
                    //])
                    ;
    }
}
