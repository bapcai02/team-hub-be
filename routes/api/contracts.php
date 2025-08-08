<?php

use App\Interfaces\Http\Controllers\ContractController;
use Illuminate\Support\Facades\Route;

Route::prefix('contracts')->group(function () {
    // Template Management (must come before dynamic routes)
    Route::get('/templates', [ContractController::class, 'getTemplates']);
    Route::post('/templates', [ContractController::class, 'createTemplate']);
    Route::put('/templates/{id}', [ContractController::class, 'updateTemplate']);
    Route::delete('/templates/{id}', [ContractController::class, 'deleteTemplate']);
    
    // Contract Management
    Route::get('/', [ContractController::class, 'getContracts']);
    Route::get('/stats', [ContractController::class, 'getStats']);
    Route::get('/{id}', [ContractController::class, 'getContract']);
    Route::post('/', [ContractController::class, 'createContract']);
    Route::put('/{id}', [ContractController::class, 'updateContract']);
    Route::delete('/{id}', [ContractController::class, 'deleteContract']);
    Route::post('/{id}/approve', [ContractController::class, 'approveContract']);
    Route::post('/{id}/sign', [ContractController::class, 'addSignature']);
    Route::post('/{id}/generate-pdf', [ContractController::class, 'generatePDF']);
}); 