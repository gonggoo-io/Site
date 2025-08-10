<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\InsertController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KakaoAuthController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\MetaController;
use App\Http\Controllers\BuyController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\NotificationController;

Route::get('/', fn () => Inertia::render('Home'));

Route::get('/privacy', fn () => Inertia::render('Privacy'))->name('privacy');

Route::middleware('guest')->group(function () {
    Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
    Route::post('/signup', [AuthController::class, 'signup']);
    Route::get('/signin', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/signin', [AuthController::class, 'login']);
    

    Route::get('/auth/kakao', [KakaoAuthController::class, 'redirectToKakao'])->name('kakao.login');
    Route::get('/auth/kakao/callback', [KakaoAuthController::class, 'handleKakaoCallback'])->name('kakao.callback');
    
    Route::post('/verification/send', [VerificationController::class, 'sendVerificationCode']);
    Route::post('/verification/code', [VerificationController::class, 'verifyCode']);
});

Route::get('/inserts', [InsertController::class, 'index']);

Route::get('/content/{id}', [ContentController::class, 'show'])->name('content');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/insert', fn () => Inertia::render('Insert/Insert'))->name('insert');
    Route::get('/auto-insert', fn () => Inertia::render('Insert/AutoInsert'))->name('auto-insert');
    Route::get('/insert-details', fn () => Inertia::render('Insert/InsertDetail'))->name('insert.details');
    Route::get('/insert-deposit', fn () => Inertia::render('Insert/InsertDeposit'))->name('insert.deposit');
    Route::post('/insert', [InsertController::class, 'store']);
    Route::delete('/insert/{id}', [InsertController::class, 'destroy'])->name('insert.destroy');
    Route::post('/insert/{id}/tracking', [InsertController::class, 'submitTrackingNumber'])->name('insert.tracking');

    Route::post('/buy', [BuyController::class, 'store'])->name('buy.store');
    Route::delete('/buy', [BuyController::class, 'destroy'])->name('buy.destroy');
    Route::get('/buy/{insertId}', [BuyController::class, 'show'])->name('buy.show');
    Route::get('/buy/{insertId}/bancheck', [BuyController::class, 'bancheck'])->name('buy.bancheck');

    Route::get('/select-insert-type', function () {
        return Inertia::render('Insert/SelectInsertType');
    })->middleware(['auth']);

    Route::get('/notifications', fn () => Inertia::render('Notifications'))->name('notifications');
    Route::get('/api/notifications', [NotificationController::class, 'index'])->name('api.notifications');
    Route::post('/notifications/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');
    Route::get('/mypage/applied', fn () => Inertia::render('Mypage/Applied'))->name('mypage.applied');
    Route::get('/mypage/shipping', fn () => Inertia::render('Mypage/Shipping'))->name('mypage.shipping');
    Route::get('/mypage/completed', fn () => Inertia::render('Mypage/Completed'))->name('mypage.completed');
    
    // Debug routes
    Route::get('/debug/my-buys', [MypageController::class, 'debugMyBuys']);
    Route::get('/debug/my-inserts', [MypageController::class, 'debugMyInserts']);
});

Route::post('/api/meta', [MetaController::class, 'fetchMeta'])->name('meta.fetch');
