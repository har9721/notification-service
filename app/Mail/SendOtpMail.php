<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mail_data;
    public $otp;

    public function __construct($data, $otp)
    {
        $this->mail_data = $data;
        $this->otp = $otp;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notification Service OTP',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.sendOtp',
            with: [
                'otp' => $this->otp,
                'name' => $this->mail_data['name']
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
