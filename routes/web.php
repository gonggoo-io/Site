<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailVerificationController;

Route::get('/', fn () => Inertia::render('Home'));

Route::middleware('guest')->group(function () {
    Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::post('/send-verification-code', [EmailVerificationController::class, 'sendVerificationCode']);
    Route::post('/verify-code', [EmailVerificationController::class, 'verifyCode']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', fn () => Inertia::render('Dashboard'))->name('dashboard');

    Route::get('/insert', fn () => Inertia::render('Insert/Insert'))->name('insert');
    Route::post('/insert', [PostController::class, 'store']);
});
