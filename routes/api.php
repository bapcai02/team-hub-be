<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\AttendanceController;
use App\Interfaces\Http\Controllers\LeaveController;
use App\Interfaces\Http\Controllers\EmployeeController;
use App\Interfaces\Http\Controllers\DepartmentController;
use App\Interfaces\Http\Controllers\CalendarController;
use App\Interfaces\Http\Controllers\DashboardController;

$apiRouteDir = __DIR__ . '/api';

foreach (glob($apiRouteDir . '/*.php') as $file) {
    require $file;
}

// Dashboard routes
Route::prefix('/dashboard')->middleware(['auth:api'])->group(function () {
    Route::get('/', [DashboardController::class, 'getDashboardData']);
    Route::get('/activities', [DashboardController::class, 'getRecentActivities']);
    Route::get('/charts', [DashboardController::class, 'getChartData']);
});

// Department routes
Route::prefix('/departments')->middleware(['auth:api'])->group(function () {
    Route::get('/', [DepartmentController::class, 'index']);
    Route::get('/{id}', [DepartmentController::class, 'show']);
});
