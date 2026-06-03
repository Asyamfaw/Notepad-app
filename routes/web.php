<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Guest Routes (belum login)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Auth Routes (sudah login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Notes
    Route::get('/notes/create', function () {
        return view('notes.create');
    })->name('notes.create');

    Route::get('/notes/{note}/edit', function (\App\Models\Note $note) {
        return view('notes.edit', compact('note'));
    })->name('notes.edit');

    // Trash
    Route::get('/trash', function () {
        return view('trash');
    })->name('trash');

    // Archive
    Route::get('/archive', function () {
        return view('archive');
    })->name('archive');

    // Categories & Tags
    Route::get('/categories', function () {
        return view('categories');
    })->name('categories');

    // Profile
    Route::get('/profile',  [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile',  [ProfileController::class, 'update'])->name('profile.update');
});

