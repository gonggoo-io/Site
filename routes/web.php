<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\InsertController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\MetaController;
use App\Models\Insert;
use App\Http\Controllers\BuyController;

Route::get('/', fn () => Inertia::render('Home'));

Route::get('/privacy', fn () => Inertia::render('Privacy'))->name('privacy');

Route::middleware('guest')->group(function () {
    Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::get('/signin', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/signin', [AuthController::class, 'login']);
    
    Route::post('/verification/send', [VerificationController::class, 'sendVerificationCode']);
    Route::post('/verification/code', [VerificationController::class, 'verifyCode']);
});

Route::get('/inserts', [InsertController::class, 'index']);

Route::get('/content/{id}', function ($id) {
    return Inertia::render('Content');
})->name('content');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () {
        $inserts = Insert::with(['user', 'buys'])->orderBy('created_at', 'desc')->get();
        return Inertia::render('Dashboard', [
            'inserts' => $inserts
        ]);
    })->name('dashboard');

    Route::get('/insert', fn () => Inertia::render('Insert/Insert'))->name('insert');
    Route::post('/insert', [InsertController::class, 'store']);

    Route::post('/buy', [BuyController::class, 'store'])->name('buy.store');
    Route::delete('/buy', [BuyController::class, 'destroy'])->name('buy.destroy');
    Route::get('/buy/{insertId}', [BuyController::class, 'show'])->name('buy.show');
});

Route::post('/api/meta', [MetaController::class, 'fetchMeta'])->name('meta.fetch');
