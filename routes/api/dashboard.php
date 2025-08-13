<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\DashboardController;


Route::prefix('/dashboard')->middleware(['auth:api'])->group(function () {
    Route::get('/', [DashboardController::class, 'getDashboardData']);
    Route::get('/activities', [DashboardController::class, 'getRecentActivities']);
    Route::get('/charts', [DashboardController::class, 'getChartData']);
});