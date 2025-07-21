<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\UserController;
use App\Interfaces\Http\Controllers\AuthController;

Route::prefix('users')->middleware(['auth:api'])->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware(['api.token', 'auth:api']);
