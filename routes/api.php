<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\MessageController;


Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
});


Route::middleware('auth:api')->group(function () {
    Route::get('/messages', [MessageController::class, 'index']);
    Route::post('/messages', [MessageController::class, 'store']);
    Route::post('/messages/seen', [MessageController::class, 'markAsSeen']);
});