<?php

use App\Events\TestEvent;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\MessagingController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/broadcast', function () {
    broadcast(new TestEvent());
});
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/send-message', [ChatController::class, 'sendMessage']);
    Route::post('/user-status', [ChatController::class, 'updateUserStatus']);
    Route::post('/mark-seen/{message}', [ChatController::class, 'markAsSeen']);
});

require __DIR__.'/auth.php';
