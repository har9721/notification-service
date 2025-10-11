<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class FirebaseChannel
{
    public function send($notifiable, Notification $notification)
    {
        // The Notification class must have a 'toFirebase()' method
        if (! method_exists($notification, 'toFirebase')) {
            return;
        }

        // Get the message payload from the notification
        $messageData = $notification->toFirebase($notifiable);

        // Send the message
        $messaging = app('firebase.messaging');

        $message = CloudMessage::withTarget('token', $messageData['token'])
            ->withNotification(FirebaseNotification::create(
                $messageData['title'],
                $messageData['body']
            ))
            ->withData($messageData['data'] ?? []);

        $messaging->send($message);
    }
}
