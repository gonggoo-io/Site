<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\InsertController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\MetaController;
use App\Models\Insert;
use App\Http\Controllers\BuyController;
use App\Models\Notification;

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
    $insert = \App\Models\Insert::with(['user', 'buys', 'activeBuys'])->findOrFail($id);
    return Inertia::render('Content', [
        'insert' => $insert
    ]);
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
    Route::get('/auto-insert', fn () => Inertia::render('Insert/AutoInsert'))->name('auto-insert');
    Route::get('/insert-details', fn () => Inertia::render('Insert/InsertDetail'))->name('insert.details');
    Route::get('/insert-deposit', fn () => Inertia::render('Insert/InsertDeposit'))->name('insert.deposit');
    Route::post('/insert', [InsertController::class, 'store']);

    Route::post('/buy', [BuyController::class, 'store'])->name('buy.store');
    Route::delete('/buy', [BuyController::class, 'destroy'])->name('buy.destroy');
    Route::get('/buy/{insertId}', [BuyController::class, 'show'])->name('buy.show');
    Route::get('/buy/{insertId}/bancheck', [BuyController::class, 'bancheck'])->name('buy.bancheck');

    Route::get('/select-insert-type', function () {
        return Inertia::render('Insert/SelectInsertType');
    })->middleware(['auth']);

    Route::get('/notifications', fn () => Inertia::render('Notifications'))->name('notifications');

    Route::get('/mypage', function () {
        return redirect('/mypage/applied');
    })->name('mypage');
    Route::get('/mypage/applied', fn () => Inertia::render('Mypage/Applied'))->name('mypage.applied');
    Route::get('/mypage/shipping', fn () => Inertia::render('Mypage/Shipping'))->name('mypage.shipping');
    Route::get('/mypage/completed', fn () => Inertia::render('Mypage/Completed'))->name('mypage.completed');
    Route::get('/debug/my-buys', function () {
        $userId = auth()->id();
        $buys = \App\Models\Buy::with(['insert.user', 'insert.buys'])
            ->where('user_id', $userId)
            ->whereNull('cancelled_at')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'user_id' => $userId,
            'buys_count' => $buys->count(),
            'buys' => $buys->toArray()
        ]);
    });

    Route::get('/debug/my-inserts', function () {
        $userId = auth()->id();
        $inserts = \App\Models\Insert::with(['user', 'buys'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'user_id' => $userId,
            'inserts_count' => $inserts->count(),
            'inserts' => $inserts->toArray()
        ]);
    });

    Route::middleware('auth')->get('/notifications', function () {
        $user = auth()->user();
        return Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($n) {
                $data = $n->data;
                return [
                    'id' => $n->id,
                    'user' => $data['user'] ?? null,
                    'action' => $data['action'] ?? null,
                    'group' => $data['group'] ?? null,
                    'date' => $data['date'] ?? $n->created_at->format('Y-m-d H:i'),
                    'read_at' => $n->read_at,
                    'title' => $data['group'] ?? null,
                    'insert_id' => $data['insert_id'] ?? null,
                ];
            });
    });
});

Route::post('/api/meta', [MetaController::class, 'fetchMeta'])->name('meta.fetch');
