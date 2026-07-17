<?php

use App\Http\Controllers\ChatsController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::redirect('/dashboard', '/chat')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/claim-admin', function () {
        if (\App\Models\User::where('is_admin', true)->count() === 0) {
            auth()->user()->update(['is_admin' => true]);
            return redirect()->route('admin.index')->with('success', 'You have successfully claimed the Super Admin role!');
        }
        return redirect()->route('chat.index')->with('error', 'An admin already exists.');
    });

    Route::get('/chat', [ChatsController::class, 'index'])->name('chat.index');
    Route::delete('/chat/{chat}', [ChatsController::class, 'destroy'])->name('chat.destroy');
    Route::get('/chat/{receiver}', [ChatsController::class, 'show'])->name('chat.show');
    Route::post('/chat/{receiver}', [ChatsController::class, 'store'])->name('chat.store');
    Route::post('/chat/{chat}/react', [\App\Http\Controllers\ReactionController::class, 'toggle'])->name('chat.react');

    Route::get('/', function () {
        return view('chat.index', [
            'users' => User::all()->except(auth()->id()),
            'receiver' => null,
            'messages' => collect(),
        ]);
    });

    Route::get('/logout', function () {
        auth()->logout();
        return redirect('login');
    });

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('index');
        Route::post('/users/{user}/toggle-admin', [\App\Http\Controllers\AdminController::class, 'toggleAdmin'])->name('users.toggle');
        Route::delete('/users/{user}', [\App\Http\Controllers\AdminController::class, 'destroyUser'])->name('users.destroy');
        Route::delete('/chats/{chat}', [\App\Http\Controllers\AdminController::class, 'destroyChat'])->name('chats.destroy');
    });

});

require __DIR__.'/auth.php';
