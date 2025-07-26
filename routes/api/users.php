<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\UserController;
use App\Interfaces\Http\Controllers\AuthController;

Route::prefix('users')->middleware(['auth:api'])->group(function () {
    // Basic CRUD operations
    Route::post('/', [UserController::class, 'store']); // Create user
    Route::get('/', [UserController::class, 'index']); // List all users
    Route::get('/{id}', [UserController::class, 'show']); // Get user details
    Route::patch('/{id}', [UserController::class, 'update']); // Update user
    Route::delete('/{id}', [UserController::class, 'destroy']); // Delete user

    // User filtering
    Route::get('/by-status', [UserController::class, 'getByStatus']); // Get users by status
    Route::get('/by-role', [UserController::class, 'getByRole']); // Get users by role
    Route::get('/active', [UserController::class, 'getActiveUsers']); // Get active users
});

// Auth routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware(['api.token', 'auth:api']);
