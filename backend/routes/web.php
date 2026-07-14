<?php

use App\Models\User;
use App\Http\Controllers\ChatsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/chat', [ChatsController::class, 'index'])->name('chat.index');
    Route::delete('/chat/{chat}', [ChatsController::class, 'destroy'])->name('chat.destroy');
    Route::get('/chat/{receiver}', [ChatsController::class, 'show'])->name('chat.show');
    Route::post('/chat/{receiver}', [ChatsController::class, 'store'])->name('chat.store');

    Route::get('/', function () {
        return view('chat.index', [
            'users' => User::all()->except(auth()->id()),
            'receiver' => null,
            'messages' => collect(),
        ]);
    });
    
});

require __DIR__.'/auth.php';
