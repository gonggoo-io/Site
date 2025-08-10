<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MypageController;

Route::get('/notifications', [NotificationController::class, 'index'])->middleware('auth:sanctum');
Route::get('/notifications/stream', [NotificationController::class, 'stream'])->middleware('auth:sanctum');
Route::post('/notifications/read', [NotificationController::class, 'markAsRead'])->middleware('auth:sanctum');
Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->middleware('auth:sanctum');

Route::middleware('auth')->group(function () {
    Route::get('/my/inserts', [MypageController::class, 'myInserts']);
    Route::get('/my/buys', [MypageController::class, 'myBuys']);
});
