<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\MessageController;


Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
});


Route::middleware('auth:sanctum')->controller(MessageController::class)->group(function () {
    Route::get('/messages', 'index');
    Route::post('/send-message', 'store');
    Route::post('/messages/seen', 'markAsSeen');
});