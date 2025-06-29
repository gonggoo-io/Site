<?php

use App\Http\Controllers\NotificationController;

Route::get('/notifications/stream', [NotificationController::class, 'stream'])->middleware('auth:sanctum');
Route::post('/notifications/read', [NotificationController::class, 'markAsRead'])->middleware('auth:sanctum');
Route::get('/notifications/unread-count', function () {
    $userId = auth()->id();
    $unreadCount = \App\Models\Notification::where('user_id', $userId)->whereNull('read_at')->count();
    return response()->json(['unread_count' => $unreadCount]);
})->middleware('auth:sanctum');
