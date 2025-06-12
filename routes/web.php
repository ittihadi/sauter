<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Routes that can be accessed with/without logging in
Route::get('/', [PostController::class, 'showHome'])->name('home');

Route::get('/post/{id}', [PostController::class, 'show']);


// Things that need authentication go here
Route::middleware(['auth', 'auth.session'])->group(function () {
    Route::get('/following', [PostController::class, 'showFollowing'])->name('feed');

    Route::post('/post/new', [PostController::class, 'store']);
    Route::post('/post/edit/{id}', [PostController::class, 'edit']);
    Route::get('/post/delete/{id}', [PostController::class, 'delete']);
    Route::post('/post/reply/{id}', [PostController::class, 'reply']);

    Route::get('/profile/me', function () {
        $username = Auth::user()->name;
        return redirect("/profile/$username");
    });
    Route::post('/profile/edit/{id}', [ProfileController::class, 'edit']);
    Route::post('/follow/{id}', [ProfileController::class, 'follow']);
    Route::post('/unfollow/{id}', [ProfileController::class, 'unfollow']);
});


// This goes after so /profile/me gets more priority
Route::get('/profile/{id}', [ProfileController::class, 'show']);


// Authentication routes
Route::get('/login', function () {
    if (Auth::check()) {
        return redirect('/');
    }

    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    if (Auth::check()) {
        return redirect('/');
    }

    return view('auth.register');
})->name('register');

Route::get('/logout', [LoginController::class, 'logout']);

Route::post('/register', [LoginController::class, 'register']);
Route::post('/login', [LoginController::class, 'authenticate']);
