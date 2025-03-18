<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\PrivateMessagesController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Group routes that require authentication
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Channel routes
    Route::get('/channels', [ChannelController::class, 'index'])->name('channels.index');
});

// Message routes for private messages
Route::get('/private-messages', [PrivateMessagesController::class, 'index'])->name('private_messages');
Route::post('/broadcast', [PrivateMessagesController::class, 'broadcast'])->name('message.broadcast');
Route::post('/receive', [PrivateMessagesController::class, 'receive'])->name('message.receive'); // Fixed typo 'reveive' to 'receive'

// Admin routes (with 'admin' middleware)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::patch('/admin/users/{user}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.destroy');

    // Channel creation routes
    Route::get('/channels/create', [ChannelController::class, 'create'])->name('channels.create');
    Route::post('/channels', [ChannelController::class, 'store'])->name('channels.store');
    Route::delete('/channels/{channel}', [ChannelController::class, 'destroy'])->name('channels.destroy');
});

Route::middleware('auth')->group(function () {
    Route::post('/solve-riddle', [UserController::class, 'solveRiddle'])->name('solve.riddle');
    Route::get('/solve-riddle', [UserController::class, 'solveForm'])->name('solve.riddle.form');
});

// Server route
Route::get('/server', [ServerController::class, 'index'])->name('server');

//create channel routes
Route::get('/server/create-channel', [ChannelController::class, 'showCreateForm'])->name('showCreateForm');
Route::post('/server/create-channel', [ChannelController::class, 'store'])->name('server.create-channel');
Route::get('/server/manage/{channel}', [ChannelController::class, 'manage'])->name('channels.manage');

Route::get('/server/{channel}', [ServerController::class, 'showChannel'])->name('server.channel');

require __DIR__.'/auth.php';
