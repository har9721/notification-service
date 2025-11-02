<?php

namespace App\Services;

use App\Models\OTP;
use App\Models\User;
use Illuminate\Http\Request;

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

    public function fetchUserList()
    {
        $users = $this->user->getUserList();

        return response()->json([
            'status' => 'success',
            'data' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
                'last_page' => $users->lastPage(),
            ],
        ]);
    }
}