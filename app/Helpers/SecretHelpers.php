<?php

use App\Models\User;

function getSecretValue($str)
{
    $secretArray = [
        '1' => '*',
        '2' => '**',
        '3' => '***',
        '4' => '****',  
        '5' => '*****',
        '6' => '******',
        '7' => '*******',
        '8' => '********',
        '9' => '*********',
        '10' => '**********'
    ];

    return $secretArray[strlen($str)] ?? '**********';
}

function generateOtp()
{
    return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT); 
}

function fetchUserList($alertVia)
{
    return User::whereNot('role_id', 1)
            ->whereIn('alert_via', $alertVia)
            ->whereNull('deleted_at')
            ->where('is_stopped_alerts' , 0)
            ->select(['id', 'name', 'mobile', 'alert_via'])
            ->get();  
}
?>