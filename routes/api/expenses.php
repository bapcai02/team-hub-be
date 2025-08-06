<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\ExpenseController;

Route::prefix('/expenses')->middleware(['auth:api'])->group(function () {
    Route::get('/', [ExpenseController::class, 'getAllExpenses']);
    Route::get('/statistics', [ExpenseController::class, 'getExpenseStatistics']);
    Route::get('/export', [ExpenseController::class, 'exportToCsv']);
    Route::get('/{id}', [ExpenseController::class, 'getExpenseById']);
    Route::post('/', [ExpenseController::class, 'createExpense']);
    Route::put('/{id}', [ExpenseController::class, 'updateExpense']);
    Route::delete('/{id}', [ExpenseController::class, 'deleteExpense']);
    Route::post('/{id}/mark-as-paid', [ExpenseController::class, 'markExpenseAsPaid']);
}); 