<?php

use App\Interfaces\Http\Controllers\AttendanceController;
use App\Interfaces\Http\Controllers\LeaveController;
use App\Interfaces\Http\Controllers\EmployeeController;
use App\Interfaces\Http\Controllers\DepartmentController;

$apiRouteDir = __DIR__ . '/api';

foreach (glob($apiRouteDir . '/*.php') as $file) {
    require $file;
}

// Department routes
Route::prefix('/departments')->middleware(['auth:api'])->group(function () {
    Route::get('/', [DepartmentController::class, 'index']);
    Route::get('/{id}', [DepartmentController::class, 'show']);
});
