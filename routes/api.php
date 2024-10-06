<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;


Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
});
