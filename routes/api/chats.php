<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\Chat\ConversationController;
use App\Interfaces\Http\Controllers\Chat\MessageController;
use App\Interfaces\Http\Controllers\Chat\NotificationController;
use App\Interfaces\Http\Controllers\Chat\FileController;

Route::middleware(['auth:api'])->group(function () {
    // Conversation routes
    Route::prefix('conversations')->group(function () {
        Route::post('/', [ConversationController::class, 'store']); // Create a conversation
        Route::get('/', [ConversationController::class, 'index']); // List conversations
        Route::get('/{id}', [ConversationController::class, 'show']); // Conversation details
        Route::patch('/{id}', [ConversationController::class, 'update']); // Rename, add/remove members
        Route::delete('/{id}', [ConversationController::class, 'destroy']); // Leave or delete a conversation

        // Message routes (per conversation)
        Route::get('/{id}/messages', [MessageController::class, 'index']); // Fetch messages
        Route::post('/{id}/messages', [MessageController::class, 'store']); // Send a message
    });

    // Message routes (by message ID)
    Route::patch('/messages/{id}', [MessageController::class, 'update']); // Edit a message
    Route::delete('/messages/{id}', [MessageController::class, 'destroy']); // Delete a message

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']); // List notifications
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']); // Mark as read

    // File/Media
    Route::post('/upload', [FileController::class, 'upload']); // Upload a file
    Route::get('/files/{id}', [FileController::class, 'show']); // Retrieve a file
});
