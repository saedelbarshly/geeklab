<?php

use App\Events\TestEvent;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

use App\Http\Controllers\MessagingController;
use App\Models\User;

Route::middleware('auth')->group(function () {
    Route::get('/chat', function () {
        $users = User::all(); // Fetch all users to select for private messaging
        return view('chat', compact('users'));
    });

    Route::post('/send-message', [MessagingController::class, 'sendPrivateMessage']);
});


require __DIR__.'/auth.php';
