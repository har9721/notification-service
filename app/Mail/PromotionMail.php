<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PromotionMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;
    protected $userDetails;

    public function __construct($data, $user)
    {
        $this->data = $data;
        $this->userDetails = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->data['subject'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.promotionMail',
            with: [
                'name' => $this->userDetails->name,
                'desc' => $this->data['desc']
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
