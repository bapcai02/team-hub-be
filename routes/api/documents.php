<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\DocumentController;

Route::prefix('/documents')->middleware(['auth:api'])->group(function () {
    // Basic CRUD operations
    Route::post('/', [DocumentController::class, 'store']); // Create document
    Route::get('/', [DocumentController::class, 'index']); // List user's documents
    Route::get('/{id}', [DocumentController::class, 'show']); // Get document details
    Route::patch('/{id}', [DocumentController::class, 'update']); // Update document
    Route::delete('/{id}', [DocumentController::class, 'destroy']); // Delete document

    // Document organization
    Route::get('/by-visibility', [DocumentController::class, 'getByVisibility']); // Get by visibility
    Route::get('/root', [DocumentController::class, 'getRootDocuments']); // Get root documents
    Route::get('/{parentId}/children', [DocumentController::class, 'getChildren']); // Get child documents
}); 