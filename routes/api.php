<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserAuthController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return "access";
});
Route::get('/start', function () {
    $log = [];

    $commands = [
        //'migrate:fresh' => 'Database migrated (fresh)',
        //'db:seed --class=StartSeeder' => 'Database seeded (StartSeeder)',
        //'db:seed --class=CountrySeeder' => 'Database seeded (CountrySeeder)',
        'cache:clear' => 'Cache cleared',
        'route:clear' => 'Route cache cleared',
        'config:clear' => 'Config cache cleared',
        'view:clear' => 'View cache cleared',
    ];

    foreach ($commands as $cmd => $message) {
        Artisan::call($cmd);
        $log[] = $message;
    }

    $log[] = '--- All tasks completed successfully ---';

    return response(implode(PHP_EOL, $log), 200)
        ->header('Content-Type', 'text/plain; charset=utf-8');
});

Route::middleware('SetLan')->group(function (){
    Route::withoutMiddleware([VerifyCsrfToken::class])->group(function() {
        Route::get('getProvinces', [HomeController::class, 'getProvinces']);
    });


    Route::middleware('logged')->withoutMiddleware([VerifyCsrfToken::class])->group(function() {
        //Route::post('userRegister', [UserAuthController::class, 'userRegister'])->name('userRegister');
        Route::post('sendOTP', [UserAuthController::class, 'sendOTP']);
        Route::post('verifyOtp', [UserAuthController::class, 'verifyOtp']);
        Route::post('userLogin', [UserAuthController::class, 'userLogin']);
        Route::post('SendOTPForgetPassword', [UserAuthController::class, 'SendOTPForgetPassword']);
        Route::post('changePassword', [UserAuthController::class, 'changePassword']);
    });
    Route::middleware(['auth:sanctum', 'checkLogin'])->withoutMiddleware([VerifyCsrfToken::class])->group(function() {
        Route::post('completeProfile', [UserAuthController::class, 'completeProfile']);
        Route::get('getUser', [UserAuthController::class, 'getUser']);
    });
});
