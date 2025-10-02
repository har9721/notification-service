<?php

namespace App\Services;

use App\Models\OTP;
use App\Models\User;

class UserServices
{
    protected $user;
    protected $otp;

    public function __construct(
        User $user,
        OTP $otp
    ){
        $this->user = $user;
        $this->otp = $otp;
    }

    public function addOrEditUser($data)
    {
        $data['role_id'] = 2;
        $data['is_otp_based_login'] = 1;

        return User::firstOrCreate([
            'email' => $data['email']
        ], $data);
    }
}