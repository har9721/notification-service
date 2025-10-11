<?php

namespace App\Notifications;

use App\Notifications\Channels\FirebaseChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendPushNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail', FirebaseChannel::class];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toFirebase(object $notifiable)
    {
        return [
            'token' => "eyaTIL9dSUI3pZIg6S8ldC:APA91bHOBhITp11geEp-3lnkcsYJ8U0dJ7tczL-OVmaPglYV0AJnIDNw1lIDUvaky_-xyvOk0OMbaRSppewAc-aoiqeCqX6VJDOW05e5V4so0_inKMbfFIA",
            'title' => "Order deliverd",
            'body' => "Your order is on the way!",
            'data' => [
                'orderID' => "VM1254"
            ]
        ];
    }
}
