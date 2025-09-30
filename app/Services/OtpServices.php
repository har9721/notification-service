<?php

namespace App\Services;

use App\Events\UserRegistered;
use App\Models\OTP;
use App\Models\User;

class OtpServices
{
    protected $otp;
    protected $user;

    public function __construct(
        OTP $otp,
        User $user
    ){
        $this->otp = $otp;
        $this->user = $user;
    }

    public function sendOtp($mobile)
    {
        $otp = generateOtp();

        $user = $this->user->where([
            'mobile' => $mobile
        ])
        ->whereNull('deleted_at')
        ->first();

        $save_otp = $this->otp->saveOtp([
            'user_id' => $user->id ?? null,
            'otp' => $otp,
            'is_used' => 0,
            'expires_at' => now()->addMinutes(5)
        ]);

        if($save_otp){
            //send otp to mobile using sms gateway
            event(new UserRegistered($user));

            return response()->json([
                'status' => 'success',
                'message' => 'OTP sent successfully',
                'data' => [
                    'mobile' => $mobile,
                    'otp' => $otp
                ]
            ], 200);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send OTP'
            ], 500);
        }
    }
}
?>