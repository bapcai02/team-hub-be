<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\DepartmentController;


Route::prefix('/departments')->middleware(['auth:api'])->group(function () {
    Route::get('/', [DepartmentController::class, 'index']);
    Route::get('/{id}', [DepartmentController::class, 'show']);
});