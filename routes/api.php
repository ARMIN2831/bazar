<?php

use App\Http\Controllers\UserAuthController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('SetLan')->group(function (){
    Route::middleware('logged')->withoutMiddleware([VerifyCsrfToken::class])->group(function() {
        //Route::post('userRegister', [UserAuthController::class, 'userRegister'])->name('userRegister');
        Route::post('sendOTP', [UserAuthController::class, 'sendOTP']);
        Route::post('verifyOtp', [UserAuthController::class, 'verifyOtp']);
        Route::post('userLogin', [UserAuthController::class, 'userLogin']);

        //Route::post('SendOTPForgetPassword', [UserAuthController::class, 'SendOTPForgetPassword']);
        //Route::post('changePassword', [UserAuthController::class, 'changePassword']);
    });
    Route::middleware(['auth:sanctum', 'checkLogin'])->withoutMiddleware([VerifyCsrfToken::class])->group(function() {
        Route::post('completeProfile', [UserAuthController::class, 'completeProfile']);
        Route::get('getUser', [UserAuthController::class, 'getUser']);
    });
});
