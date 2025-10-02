<?php

namespace App\Jobs;

use App\Mail\sendWelcomeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportException;

class sendWelcomeEmailJob implements ShouldQueue
{
    use Queueable;

    public $user;

    public $tries = 3; //max attemps after job fails

    public $backoff = [20, 45, 95]; // delay : 1st retry after 10 sec, 2nd after 30 sec, 3rd after 60 sec

    public $timeout = 60; //60 sec

    public function __construct($data)
    {
        info('sendWelcomeEmailJob Initialized');
        $this->user = $data;
    }

    public function handle(): void
    {
        try{
            Mail::to($this->user->email)
                ->send(
                    new sendWelcomeMail(
                        $this->user->toArray()
                    )
                );
        }
        catch(TransportException $e)
        {
            if(str_contains($e->getMessage(), 'Connection could not be established with host')) 
            {
                throw $e; // re-throw the exception to trigger a retry
            }
            else if(str_contains($e->getMessage(), 'Too many emails per second'))
            {
                throw $e; // re-throw the exception to trigger a retry
            }

        }
    }
}
