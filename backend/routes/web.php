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
    Route::get('/chat', [ChatsController::class, 'index'])->name('chat.index');
    Route::delete('/chat/{chat}', [ChatsController::class, 'destroy'])->name('chat.destroy');
    Route::post('/chat/{chat}/hide', [ChatsController::class, 'hide'])->name('chat.hide');
    Route::get('/chat/{receiver}', [ChatsController::class, 'show'])->name('chat.show');
    Route::post('/chat/{receiver}', [ChatsController::class, 'store'])->name('chat.store');
    Route::post('/chat/{chat}/react', [\App\Http\Controllers\ReactionController::class, 'toggle'])->name('chat.react');

    Route::post('/friend/request', [\App\Http\Controllers\FriendshipController::class, 'sendRequest'])->name('friend.request');
    Route::post('/friend/{friendship}/accept', [\App\Http\Controllers\FriendshipController::class, 'acceptRequest'])->name('friend.accept');
    Route::post('/friend/{friendship}/decline', [\App\Http\Controllers\FriendshipController::class, 'declineRequest'])->name('friend.decline');

    Route::post('/group', [\App\Http\Controllers\GroupController::class, 'store'])->name('group.store');
    Route::get('/group/{group}', [\App\Http\Controllers\GroupController::class, 'show'])->name('group.show');
    Route::post('/group/{group}/member', [\App\Http\Controllers\GroupController::class, 'addMember'])->name('group.member');
    Route::post('/group/{group}/chat', [ChatsController::class, 'storeGroup'])->name('group.chat');

    Route::get('/', function () {
        return redirect()->route('chat.index');
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
