<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ScheduleNotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:api')->group(function(){
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [LoginController::class, 'register']);
    Route::post("get-otp", [LoginController::class, 'getOtp'])->name('getOtp');
    Route::post("verify-otp", [LoginController::class, 'verifyOtp'])->name('verifyOtp');
});

Route::middleware([
    'auth:sanctum',
    'throttle:api'
])->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/user-list',[LoginController::class,'getUserList']);

    Route::prefix('/schedule-notification')->group(function(){
        Route::controller(ScheduleNotificationController::class)->group(function () {
            Route::post('/create', 'store');
            Route::post('/list', 'fetch');
        });
    });
});

