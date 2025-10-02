<?php

namespace App\Services;

use App\Events\UserRegistered;
use App\Models\OTP;
use App\Models\User;
use Carbon\Carbon;

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
            'expires_at' => Carbon::now()->addMinutes(5)
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

    public function verifyOtp($mobile, $otp) 
    {
        $isValidUser = $this->user->verifyUser($mobile);

        $isValidOtp = $this->otp->verifyOtp($isValidUser->id ?? null, $otp);

        if(empty($isValidOtp))
        {
            $response = [
                'status' => 'error',
                'message' => 'OTP not found!'
            ];
        }
        
        if(!empty($isValidOtp))
        {
            $this->otp->markOtpAsUsed($isValidOtp->id);

            //generate login token
            $token = $this->user->generateLoginToken($isValidUser);

            $response = [
                'status' => 'success',
                'message' => 'OTP verified successfully',
                'user' => $isValidUser,
                'token' => $token ?? null,
                'token_type' => 'Bearer'
            ];
        }

        if($isValidOtp && $isValidOtp->is_used == 1)
        {
            $response = [
                'status' => 'error',
                'message' => 'OTP already used!'
            ];
        }

        if(Carbon::now()->greaterThan($isValidOtp->expires_at))
        {
            $response = [
                'status' => 'error',
                'message' => 'OTP expired!'
            ];
        }

        return  $response;
    }
}
?>