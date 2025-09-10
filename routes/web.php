<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/apiTester', function () {
    return view('testWorkingAPI');
});
