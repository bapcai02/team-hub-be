<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| Settings API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register settings API routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->group(function () {
    Route::prefix('settings')->group(function () {
        // Get all user settings
        Route::get('/', [SettingsController::class, 'index']);
        
        // Update profile settings
        Route::put('/profile', [SettingsController::class, 'updateProfile']);
        
        // Update app settings
        Route::put('/app', [SettingsController::class, 'updateApp']);
        
        // Update notification preferences
        Route::put('/notifications', [SettingsController::class, 'updateNotifications']);
        
        // Update security settings
        Route::put('/security', [SettingsController::class, 'updateSecurity']);
        
        // Update privacy settings
        Route::put('/privacy', [SettingsController::class, 'updatePrivacy']);
        
        // Update accessibility settings
        Route::put('/accessibility', [SettingsController::class, 'updateAccessibility']);
        
        // Export user data
        Route::get('/export', [SettingsController::class, 'exportData']);
    });
}); 