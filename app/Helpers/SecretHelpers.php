<?php

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
?>