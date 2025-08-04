<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\ExpenseController;

Route::prefix('/expenses')->middleware(['auth:api'])->group(function () {
    Route::get('/', [ExpenseController::class, 'index']);
    Route::get('/statistics', [ExpenseController::class, 'getStatistics']);
    Route::get('/{id}', [ExpenseController::class, 'show']);
    Route::post('/', [ExpenseController::class, 'store']);
    Route::put('/{id}', [ExpenseController::class, 'update']);
    Route::delete('/{id}', [ExpenseController::class, 'destroy']);
    Route::post('/{id}/approve', [ExpenseController::class, 'approve']);
    Route::post('/{id}/reject', [ExpenseController::class, 'reject']);
    Route::post('/{id}/mark-as-paid', [ExpenseController::class, 'markAsPaid']);
}); 