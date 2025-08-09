<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\GuestController;

// Test route without auth
Route::get('/guests/test', function () {
    return response()->json(['message' => 'Guests API is working']);
});

// Public routes (no auth required)
Route::prefix('guests')->group(function () {
    Route::get('/', [GuestController::class, 'index']); // List all guests
    Route::get('/{id}', [GuestController::class, 'show']); // Get guest details
    Route::get('/by-type', [GuestController::class, 'getByType']); // Get guests by type
    Route::get('/by-status', [GuestController::class, 'getByStatus']); // Get guests by status
    Route::get('/search', [GuestController::class, 'search']); // Search guests
    Route::get('/active', [GuestController::class, 'getActiveGuests']); // Get active guests
    Route::get('/recent-visits', [GuestController::class, 'getRecentVisits']); // Get recent visits
});

// Protected routes (auth required)
Route::prefix('guests')->middleware(['auth:api'])->group(function () {
    // Basic CRUD operations
    Route::post('/', [GuestController::class, 'store']); // Create guest
    Route::patch('/{id}', [GuestController::class, 'update']); // Update guest
    Route::delete('/{id}', [GuestController::class, 'destroy']); // Delete guest
}); 