<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PromotionalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $notificationData;

    public function __construct($data)
    {
        $this->notificationData = $data;

        $this->queue = 'low_priority_queue';
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->notificationData['subject'])
            ->line($this->notificationData['desc'])
            ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
