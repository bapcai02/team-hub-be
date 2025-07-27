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
Route::middleware('auth:api')->group(function () {
    Route::get('/leaves', [LeaveController::class, 'index']);
    Route::post('/leaves', [LeaveController::class, 'store']);
    Route::get('/leaves/{id}', [LeaveController::class, 'show']);
    Route::put('/leaves/{id}', [LeaveController::class, 'update']);
    Route::post('/leaves/{id}/cancel', [LeaveController::class, 'cancel']);
    Route::get('/leaves/all', [LeaveController::class, 'getAllLeaves']);
    Route::post('/leaves/{id}/approve', [LeaveController::class, 'approve']);
    Route::get('/leaves/balance', [LeaveController::class, 'getBalance']);
    Route::get('/leaves/calendar', [LeaveController::class, 'getCalendar']);
});