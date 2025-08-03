<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\MeetingController;

Route::middleware(['auth:api'])->group(function () {
    // Meeting management routes
    Route::prefix('/meetings')->group(function () {
        Route::get('/', [MeetingController::class, 'index']); // List all meetings
        Route::post('/', [MeetingController::class, 'store']); // Create new meeting
        
        // Meeting statistics (must come before {id} routes)
        Route::get('/stats', [MeetingController::class, 'getStats']); // Get meeting statistics
        Route::get('/calendar', [MeetingController::class, 'getCalendar']); // Get meetings calendar
        Route::get('/upcoming', [MeetingController::class, 'getUpcoming']); // Get upcoming meetings
        Route::get('/my-meetings', [MeetingController::class, 'getMyMeetings']); // Get user's meetings
        
        // Meeting details and management
        Route::get('/{id}', [MeetingController::class, 'show']); // Get meeting details
        Route::put('/{id}', [MeetingController::class, 'update']); // Update meeting
        Route::delete('/{id}', [MeetingController::class, 'destroy']); // Delete meeting
        
        // Meeting participants
        Route::post('/{id}/participants', [MeetingController::class, 'addParticipants']); // Add participants
        Route::delete('/{id}/participants/{userId}', [MeetingController::class, 'removeParticipant']); // Remove participant
        
        // Meeting status
        Route::post('/{id}/start', [MeetingController::class, 'startMeeting']); // Start meeting
        Route::post('/{id}/end', [MeetingController::class, 'endMeeting']); // End meeting
        Route::post('/{id}/cancel', [MeetingController::class, 'cancelMeeting']); // Cancel meeting
    });
}); 