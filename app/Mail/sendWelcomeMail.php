<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class sendWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mail_data;

    public function __construct($data)
    {
        info('sendWelcomeMail Mailable Initialized for user: ' . $data['email']);
        $this->mail_data = $data;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send Welcome Mail',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.welcome',
            with : [
                'url' => "http://127.0.0.1:8000/",
                'name' => $this->mail_data['name'],
                'email' => $this->mail_data['email'],
                'password' => $this->mail_data['password'],
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
