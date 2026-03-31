<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Crée une nouvelle instance du message.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Construit le message.
     */
    public function build()
    {
        return $this->subject('Nouveau message de contact')
                    ->view('emails.contact_admin')
                    ->with('data', $this->data);
    }
}
