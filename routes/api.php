<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MypageController;

Route::get('/notifications/stream', [NotificationController::class, 'stream'])->middleware('auth:sanctum');
Route::post('/notifications/read', [NotificationController::class, 'markAsRead'])->middleware('auth:sanctum');
Route::get('/notifications/unread-count', function () {
    $userId = auth()->id();
    $unreadCount = \App\Models\Notification::where('user_id', $userId)->whereNull('read_at')->count();
    return response()->json(['unread_count' => $unreadCount]);
})->middleware('auth:sanctum');

Route::middleware('auth')->group(function () {
    Route::get('/my/inserts', [MypageController::class, 'myInserts']);
    Route::get('/my/buys', [MypageController::class, 'myBuys']);
});
