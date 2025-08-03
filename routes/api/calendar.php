<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\CalendarController;

Route::middleware(['auth:api'])->group(function () {
    // Calendar routes
    Route::prefix('/calendar')->group(function () {
        Route::get('/events', [CalendarController::class, 'getEvents']); // Get events for date range
        Route::post('/events', [CalendarController::class, 'createEvent']); // Create new event
        Route::put('/events/{id}', [CalendarController::class, 'updateEvent']); // Update event
        Route::delete('/events/{id}', [CalendarController::class, 'deleteEvent']); // Delete event
        
        // Calendar statistics and data
        Route::get('/stats', [CalendarController::class, 'getStats']); // Get calendar statistics
        Route::get('/upcoming', [CalendarController::class, 'getUpcomingEvents']); // Get upcoming events
        Route::get('/today', [CalendarController::class, 'getTodayEvents']); // Get today's events
        Route::get('/by-type', [CalendarController::class, 'getEventsByType']); // Get events by type
    });
}); 