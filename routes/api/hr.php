<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\AttendanceController;
use App\Interfaces\Http\Controllers\LeaveController;
use App\Interfaces\Http\Controllers\EmployeeController;

// Employee management routes
Route::prefix('/employees')->middleware(['auth:api'])->group(function () {
    Route::get('/', [EmployeeController::class, 'index']);
    Route::post('/', [EmployeeController::class, 'store']);
    Route::get('/stats', [EmployeeController::class, 'getStats']);
    Route::get('/{id}', [EmployeeController::class, 'show']);
    Route::put('/{id}', [EmployeeController::class, 'update']);
    Route::delete('/{id}', [EmployeeController::class, 'destroy']);
    
    // Employee details routes
    Route::get('/{id}/time-logs', [EmployeeController::class, 'getTimeLogs']);
    Route::post('/{id}/time-logs', [EmployeeController::class, 'addTimeLog']);
    Route::get('/{id}/leaves', [EmployeeController::class, 'getLeaves']);
    Route::get('/{id}/payrolls', [EmployeeController::class, 'getPayrolls']);
    Route::get('/{id}/performances', [EmployeeController::class, 'getPerformances']);
    Route::get('/{id}/evaluations', [EmployeeController::class, 'getEvaluations']);
});

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

// Attendance management routes (for admin)
Route::prefix('/attendances')->middleware(['auth:api'])->group(function () {
    Route::get('/', [AttendanceController::class, 'getAllAttendances']); // Get all attendances with filters
    Route::get('/stats', [AttendanceController::class, 'getAttendanceStats']); // Get attendance statistics
    Route::post('/', [AttendanceController::class, 'createAttendance']); // Create new attendance record
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
    Route::post('/leaves/{id}/reject', [LeaveController::class, 'reject']); // Add reject endpoint
    Route::get('/leaves/balance', [LeaveController::class, 'getBalance']);
    Route::get('/leaves/calendar', [LeaveController::class, 'getCalendar']);
    Route::get('/leaves/stats', [LeaveController::class, 'getLeaveStats']); // Add leave stats endpoint
});