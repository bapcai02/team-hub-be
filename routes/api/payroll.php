<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\PayrollController;

Route::prefix('/payroll')->middleware(['auth:api'])->group(function () {
    Route::get('/', [PayrollController::class, 'index']);
    Route::post('/generate', [PayrollController::class, 'generatePayroll']);
    Route::get('/{id}', [PayrollController::class, 'show']);
    Route::post('/', [PayrollController::class, 'store']);
    Route::put('/{id}', [PayrollController::class, 'update']);
    Route::delete('/{id}', [PayrollController::class, 'destroy']);
    Route::post('/{id}/approve', [PayrollController::class, 'approve']);
    Route::post('/{id}/mark-as-paid', [PayrollController::class, 'markAsPaid']);
}); 