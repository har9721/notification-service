<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    protected $table = "otp";

    protected $fillable = [
        'user_id',
        'otp',
        'is_used',
        'expires_at'
    ];

    public function saveOtp($data)
    {
        return OTP::create($data); 
    }

    public function getUserOtp($user_id)
    {
        return OTP::where([
            'user_id' => $user_id,
            'is_used' => 0
        ])
        ->orderBy('created_at', 'desc')
        ->first('otp');
    }
}
