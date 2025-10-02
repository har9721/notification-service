<?php

namespace App\Jobs;

use App\Mail\SendOtpMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportException;

class sendOtpEmail implements ShouldQueue
{
    use Queueable;

    public $user;
    public $otp;

    public $tries = 5; //max attemps after job fails

    public $backoff = [5, 15, 30, 60]; // delay : 1st retry after 10 sec, 2nd after 30 sec, 3rd after 60 sec

    public $timeout = 60; //60 sec

    public function __construct($data, $otp)
    {
        $this->user = $data;
        $this->otp = $otp;
    }

    public function handle(): void
    {
        info('User Registered Event Triggered for user: ' . $this->user->email);

        try{
            Mail::to($this->user->email)
                ->send(
                    new SendOtpMail(
                        $this->user->toArray(),
                        $this->otp
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
