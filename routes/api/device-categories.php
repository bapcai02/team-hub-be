<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\DeviceCategoryController;

Route::prefix('/device-categories')->middleware(['auth:api'])->group(function () {
    Route::get('/', [DeviceCategoryController::class, 'index']);
    Route::get('/{id}', [DeviceCategoryController::class, 'show']);
    Route::post('/', [DeviceCategoryController::class, 'store']);
    Route::put('/{id}', [DeviceCategoryController::class, 'update']);
    Route::delete('/{id}', [DeviceCategoryController::class, 'destroy']);
}); 