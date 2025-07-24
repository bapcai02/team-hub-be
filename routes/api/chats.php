<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\Chat\ConversationController;
use App\Interfaces\Http\Controllers\Chat\MessageController;
use App\Interfaces\Http\Controllers\Chat\NotificationController;
use App\Interfaces\Http\Controllers\Chat\FileController;
use App\Http\Controllers\ChatController;

Route::middleware(['auth:api'])->group(function () {
    // Conversation routes
    Route::prefix('conversations')->group(function () {
        Route::post('/', [ConversationController::class, 'store']); // Tạo phòng
        Route::get('/', [ConversationController::class, 'index']); // Danh sách phòng
        Route::get('/{id}', [ConversationController::class, 'show']); // Chi tiết phòng
        Route::patch('/{id}', [ConversationController::class, 'update']); // Đổi tên, thêm/xóa thành viên
        Route::delete('/{id}', [ConversationController::class, 'destroy']); // Rời hoặc xóa phòng

        // Message routes (theo conversation)
        Route::get('/{id}/messages', [MessageController::class, 'index']); // Lấy tin nhắn
        Route::post('/{id}/messages', [MessageController::class, 'store']); // Gửi tin nhắn
    });

    // Message routes (theo message id)
    Route::patch('/messages/{id}', [MessageController::class, 'update']); // Sửa tin nhắn
    Route::delete('/messages/{id}', [MessageController::class, 'destroy']); // Xóa tin nhắn

    // Notification
    Route::get('/notifications', [NotificationController::class, 'index']); // Danh sách thông báo
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']); // Đánh dấu đã đọc

    // File/Media
    Route::post('/upload', [FileController::class, 'upload']); // Upload file
    Route::get('/files/{id}', [FileController::class, 'show']); // Lấy file

    // Chat
    Route::post('/chat/send', [ChatController::class, 'send']);
});
