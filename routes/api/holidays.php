<?php

use App\Interfaces\Http\Controllers\HolidayController;
use Illuminate\Support\Facades\Route;

// Temporary: Remove auth middleware for testing
Route::prefix('holidays')->group(function () {
    // Basic CRUD operations
    Route::get('/', [HolidayController::class, 'index']); // Get all holidays
    Route::post('/', [HolidayController::class, 'store']); // Create holiday
    Route::get('/{id}', [HolidayController::class, 'show']); // Get holiday details
    Route::put('/{id}', [HolidayController::class, 'update']); // Update holiday
    Route::delete('/{id}', [HolidayController::class, 'destroy']); // Delete holiday
    
    // Additional endpoints
    Route::get('/year/{year}', [HolidayController::class, 'getByYear']); // Get holidays by year
    Route::get('/type/{type}', [HolidayController::class, 'getByType']); // Get holidays by type
    Route::get('/active/list', [HolidayController::class, 'getActive']); // Get active holidays
    Route::get('/upcoming/list', [HolidayController::class, 'getUpcoming']); // Get upcoming holidays
    Route::get('/check/date', [HolidayController::class, 'checkHoliday']); // Check if date is holiday
}); 