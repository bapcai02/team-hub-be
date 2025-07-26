<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\AttendanceController;
use App\Interfaces\Http\Controllers\LeaveController;

// Attendance routes
Route::prefix('/attendance')->middleware(['auth:api'])->group(function () {
    // Employee attendance actions
    Route::post('/check-in', [AttendanceController::class, 'checkIn']); // Check in
    Route::post('/check-out', [AttendanceController::class, 'checkOut']); // Check out
    Route::post('/break/start', [AttendanceController::class, 'startBreak']); // Start break
    Route::post('/break/end', [AttendanceController::class, 'endBreak']); // End break
    
    // Employee attendance queries
    Route::get('/today', [AttendanceController::class, 'getTodayAttendance']); // Get today's attendance
    Route::get('/history', [AttendanceController::class, 'getHistory']); // Get attendance history
    Route::get('/summary', [AttendanceController::class, 'getSummary']); // Get attendance summary
    
    // Admin attendance queries
    Route::get('/all', [AttendanceController::class, 'getAllAttendance']); // Get all employees attendance
});

// Leave management routes
Route::prefix('/leaves')->middleware(['auth:api'])->group(function () {
    // Employee leave actions
    Route::get('/', [LeaveController::class, 'index']); // Get my leave requests
    Route::post('/', [LeaveController::class, 'store']); // Create leave request
    Route::get('/{id}', [LeaveController::class, 'show']); // Get leave details
    Route::patch('/{id}', [LeaveController::class, 'update']); // Update leave request
    Route::post('/{id}/cancel', [LeaveController::class, 'cancel']); // Cancel leave request
    
    // Employee leave queries
    Route::get('/balance/me', [LeaveController::class, 'getBalance']); // Get my leave balance
    
    // Admin leave management
    Route::get('/admin/all', [LeaveController::class, 'getAllLeaves']); // Get all leave requests
    Route::post('/{id}/approve', [LeaveController::class, 'approve']); // Approve/reject leave
    Route::get('/admin/calendar', [LeaveController::class, 'getCalendar']); // Get leave calendar
});