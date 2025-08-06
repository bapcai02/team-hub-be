<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\AnalyticsController;

Route::prefix('/analytics')->middleware(['auth:api'])->group(function () {
    Route::get('/', [AnalyticsController::class, 'getAnalytics']);
    Route::get('/employee', [AnalyticsController::class, 'getEmployeeAnalytics']);
    Route::get('/financial', [AnalyticsController::class, 'getFinancialAnalytics']);
    Route::get('/project', [AnalyticsController::class, 'getProjectAnalytics']);
    Route::get('/attendance', [AnalyticsController::class, 'getAttendanceAnalytics']);
    Route::get('/kpi', [AnalyticsController::class, 'getKPIMetrics']);
    Route::get('/trends', [AnalyticsController::class, 'getTrendAnalysis']);
    Route::get('/reports', [AnalyticsController::class, 'getCustomReport']);
    Route::get('/export', [AnalyticsController::class, 'exportAnalyticsReport']);
}); 