<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\InsertController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;

Route::get('/', fn () => Inertia::render('Home'));

Route::middleware('guest')->group(function () {
    Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::get('/signin', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/signin', [AuthController::class, 'login']);
    
    Route::post('/verification/send', [VerificationController::class, 'sendVerificationCode']);
    Route::post('/verification/code', [VerificationController::class, 'verifyCode']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', fn () => Inertia::render('Dashboard'))->name('dashboard');

    Route::get('/insert', fn () => Inertia::render('Insert/Insert'))->name('insert');
    Route::post('/insert', [InsertController::class, 'store']);
});
