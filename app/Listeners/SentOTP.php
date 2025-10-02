<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\SendOtpMail;
use App\Models\OTP;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SentOTP implements ShouldQueue
{
    private $otp;

    public function __construct(OTP $otp)
    {
        $this->otp = $otp;
    }

    public function handle(UserRegistered $event): void
    {
        info('User Registered Event Triggered for user: ' . $event->user->email);

        $otp = $this->otp->getUserOtp($event->user->id);
        info($otp);

        Mail::to($event->user->email)
            ->send(
                new SendOtpMail(
                    $event->user->toArray(),
                    $otp->otp
                )
            );
    }
}
