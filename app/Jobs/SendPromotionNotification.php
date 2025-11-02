<?php

namespace App\Jobs;

use App\Mail\PromotionMail;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendPromotionNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, Batchable;

    protected $notificationData;
    protected $userDetails;

    public $tries = 3; //max attemps after job fails

    public $backoff = [60];

    public $timeout = 60; //60 sec

    public function __construct($user,$data)
    {
        info('-------------------------------inside job----------------------------');
        info($data);
        info('------------------------------user--------------------------');
        info($user);
        $this->notificationData = $data;
        $this->userDetails = $user;
    }

    public function handle(): void
    {
        Mail::to($this->userDetails->email)
        ->send(
            new PromotionMail(
                $this->notificationData,
                $this->userDetails
            )
        );  
        info('-------------------Inside SendPromotionNotification Job-------------------');
        info('Sending promotional email to: '.$this->userDetails->email);
        info('add a delay of 5 seconds to simulate email sending process');
        // sleep(5);
        info('---------------------------end of job--------------------------------');

    }
}
