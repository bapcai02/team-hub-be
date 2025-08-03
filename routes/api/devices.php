<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\DeviceController;

Route::prefix('/devices')->middleware(['auth:api'])->group(function () {
    Route::get('/', [DeviceController::class, 'index']);
    Route::get('/stats', [DeviceController::class, 'getStats']);
    Route::get('/search', [DeviceController::class, 'search']);
    Route::get('/{id}', [DeviceController::class, 'show']);
    Route::post('/', [DeviceController::class, 'store']);
    Route::put('/{id}', [DeviceController::class, 'update']);
    Route::delete('/{id}', [DeviceController::class, 'destroy']);
    Route::post('/{id}/assign', [DeviceController::class, 'assign']);
    Route::post('/{id}/unassign', [DeviceController::class, 'unassign']);
    Route::get('/{id}/history', [DeviceController::class, 'getHistory']);
}); 