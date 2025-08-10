<?php

use App\Interfaces\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('notifications')->middleware(['auth:api'])->group(function () {
    // Get user notifications
    Route::get('/', [NotificationController::class, 'getUserNotifications']);
    
    // Mark notification as read
    Route::patch('/{id}/read', [NotificationController::class, 'markAsRead']);
    
    // Mark all notifications as read
    Route::patch('/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    
    // Get notification statistics
    Route::get('/stats', [NotificationController::class, 'getNotificationStats']);
    
    // Send notification
    Route::post('/send', [NotificationController::class, 'sendNotification']);
    
    // Send template notification
    Route::post('/send-template', [NotificationController::class, 'sendTemplateNotification']);
    
    // Get notification preferences
    Route::get('/preferences', [NotificationController::class, 'getNotificationPreferences']);
    
    // Update notification preferences
    Route::put('/preferences', [NotificationController::class, 'updateNotificationPreferences']);
    
    // Get notification templates
    Route::get('/templates', [NotificationController::class, 'getNotificationTemplates']);
    
    // Create notification template
    Route::post('/templates', [NotificationController::class, 'createNotificationTemplate']);
    
    // Update notification template
    Route::put('/templates/{id}', [NotificationController::class, 'updateNotificationTemplate']);
    
    // Delete notification template
    Route::delete('/templates/{id}', [NotificationController::class, 'deleteNotificationTemplate']);
    
    // Get notification categories
    Route::get('/categories', [NotificationController::class, 'getNotificationCategories']);
    
    // Get notification channels
    Route::get('/channels', [NotificationController::class, 'getNotificationChannels']);
    
    // Get notification priorities
    Route::get('/priorities', [NotificationController::class, 'getNotificationPriorities']);
}); 