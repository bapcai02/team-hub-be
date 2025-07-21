<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\ProjectController;

Route::prefix('/projects')->middleware(['auth:api'])->group(function () {
    Route::post('/', [ProjectController::class, 'store']);
    Route::get('/', [ProjectController::class, 'index']);
});
