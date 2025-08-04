<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\SalaryComponentController;

Route::prefix('/salary-components')->middleware(['auth:api'])->group(function () {
    Route::get('/', [SalaryComponentController::class, 'index']);
    Route::get('/type/{type}', [SalaryComponentController::class, 'getByType']);
    Route::get('/{id}', [SalaryComponentController::class, 'show']);
    Route::post('/', [SalaryComponentController::class, 'store']);
    Route::put('/{id}', [SalaryComponentController::class, 'update']);
    Route::delete('/{id}', [SalaryComponentController::class, 'destroy']);
    Route::post('/{id}/toggle-active', [SalaryComponentController::class, 'toggleActive']);
}); 