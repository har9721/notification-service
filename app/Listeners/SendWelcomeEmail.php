<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\sendWelcomeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail implements ShouldQueue
{
    public function __construct()
    {
        info('SendWelcomeEmail Listener Initialized');
    }

    public function handle(UserRegistered $event): void
    {
        info('User Registered Event Triggered for user: ' . $event->user->email);

        Mail::to($event->user->email)
            ->send(
                new sendWelcomeMail(
                    $event->user->toArray()
                )
            );
    }
}
