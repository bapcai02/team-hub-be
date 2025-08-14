<?php

use App\Http\Controllers\Api\CalendarController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    // Calendar Events
    Route::prefix('calendar-events')->group(function () {
        Route::get('/', [CalendarController::class, 'index']);
        Route::get('/upcoming', [CalendarController::class, 'upcoming']);
        Route::get('/type/{type}', [CalendarController::class, 'byType']);
        Route::get('/date-range', [CalendarController::class, 'byDateRange']);
        Route::get('/search', [CalendarController::class, 'search']);
        Route::get('/{id}', [CalendarController::class, 'show']);
        Route::post('/', [CalendarController::class, 'store']);
        Route::put('/{id}', [CalendarController::class, 'update']);
        Route::delete('/{id}', [CalendarController::class, 'destroy']);
    });
}); 