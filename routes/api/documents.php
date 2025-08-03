<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\DocumentController;

Route::prefix('/documents')->middleware(['auth:api'])->group(function () {
    Route::get('/', [DocumentController::class, 'index']);
    Route::get('/stats', [DocumentController::class, 'getStats']);
    Route::get('/search', [DocumentController::class, 'search']);
    Route::get('/{id}', [DocumentController::class, 'show']);
    Route::post('/', [DocumentController::class, 'store']);
    Route::put('/{id}', [DocumentController::class, 'update']);
    Route::delete('/{id}', [DocumentController::class, 'destroy']);
    Route::get('/{id}/download', [DocumentController::class, 'download']);
    Route::post('/{id}/comments', [DocumentController::class, 'addComment']);
    Route::get('/{id}/comments', [DocumentController::class, 'getComments']);
}); 