<?php

use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:api');
Route::post('/register', [LoginController::class, 'register']);
Route::post("get-otp", [LoginController::class, 'getOtp'])->name('getOtp');
Route::post("verify-otp", [LoginController::class, 'verifyOtp'])->name('verifyOtp');

Route::middleware([
    'auth:sanctum',
    'throttle:api'
])->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

});

