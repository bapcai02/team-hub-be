<?php

use Illuminate\Support\Facades\Route;
use App\Interfaces\Http\Controllers\ProjectController;
use App\Interfaces\Http\Controllers\TaskController;
use App\Interfaces\Http\Controllers\TaskLogController;
use App\Interfaces\Http\Controllers\KanbanController;
use App\Interfaces\Http\Controllers\TaskCommentController;
use App\Interfaces\Http\Controllers\TaskAttachmentController;

Route::prefix('/projects')->middleware(['auth:api'])->group(function () {
    // Basic CRUD operations
    Route::post('/', [ProjectController::class, 'store']); // Create project
    Route::get('/', [ProjectController::class, 'index']); // List projects
    Route::get('/{id}', [ProjectController::class, 'show']); // Get project details
    Route::patch('/{id}', [ProjectController::class, 'update']); // Update project
    Route::delete('/{id}', [ProjectController::class, 'destroy']); // Delete project

    // Project statistics
    Route::get('/{id}/statistics', [ProjectController::class, 'statistics']); // Get project stats

    // Member management
    Route::get('/{id}/members', [ProjectController::class, 'members']); // Get project members
    Route::post('/{id}/members', [ProjectController::class, 'addMembers']); // Add members
    Route::delete('/{id}/members', [ProjectController::class, 'removeMembers']); // Remove members

    // Task management
    Route::prefix('/{projectId}/tasks')->group(function () {
        Route::post('/', [TaskController::class, 'store']); // Create task
        Route::get('/', [TaskController::class, 'index']); // List tasks
        Route::get('/by-status', [TaskController::class, 'getByStatus']); // Get tasks by status
    });

    // Kanban board management
    Route::prefix('/{projectId}/kanban')->group(function () {
        Route::get('/board', [KanbanController::class, 'getBoard']); // Get kanban board
        Route::post('/columns', [KanbanController::class, 'createColumn']); // Create column
        Route::patch('/columns/{id}', [KanbanController::class, 'updateColumn']); // Update column
        Route::delete('/columns/{id}', [KanbanController::class, 'deleteColumn']); // Delete column
        Route::post('/reorder', [KanbanController::class, 'reorderColumns']); // Reorder columns
        Route::post('/initialize', [KanbanController::class, 'initializeDefaultColumns']); // Initialize default columns
    });
});

// Global task routes
Route::prefix('/tasks')->middleware(['auth:api'])->group(function () {
    Route::get('/{id}', [TaskController::class, 'show']); // Get task details
    Route::patch('/{id}', [TaskController::class, 'update']); // Update task
    Route::delete('/{id}', [TaskController::class, 'destroy']); // Delete task
    Route::get('/my-tasks', [TaskController::class, 'getByAssignee']); // Get my assigned tasks

    // Task time tracking
    Route::prefix('/{taskId}/time')->group(function () {
        Route::post('/start', [TaskLogController::class, 'startTime']); // Start time tracking
        Route::post('/stop', [TaskLogController::class, 'stopTime']); // Stop time tracking
        Route::get('/logs', [TaskLogController::class, 'getByTask']); // Get time logs for task
        Route::get('/active', [TaskLogController::class, 'getActiveLog']); // Get active time log
        Route::get('/total', [TaskLogController::class, 'getTotalTimeByTask']); // Get total time for task
    });

    // Task comments
    Route::prefix('/{taskId}/comments')->group(function () {
        Route::get('/', [TaskCommentController::class, 'index']); // Get task comments
        Route::post('/', [TaskCommentController::class, 'store']); // Add comment
    });

    // Task attachments
    Route::prefix('/{taskId}/attachments')->group(function () {
        Route::get('/', [TaskAttachmentController::class, 'index']); // Get task attachments
        Route::post('/', [TaskAttachmentController::class, 'store']); // Upload attachment
    });
});

// Global comment routes
Route::prefix('/comments')->middleware(['auth:api'])->group(function () {
    Route::patch('/{id}', [TaskCommentController::class, 'update']); // Update comment
    Route::delete('/{id}', [TaskCommentController::class, 'destroy']); // Delete comment
});

// Global attachment routes
Route::prefix('/attachments')->middleware(['auth:api'])->group(function () {
    Route::get('/{id}/download', [TaskAttachmentController::class, 'download']); // Download attachment
    Route::delete('/{id}', [TaskAttachmentController::class, 'destroy']); // Delete attachment
});

// Time tracking routes
Route::prefix('/time-tracking')->middleware(['auth:api'])->group(function () {
    Route::get('/my-logs', [TaskLogController::class, 'getMyLogs']); // Get my time logs
    Route::get('/my-total', [TaskLogController::class, 'getMyTotalTime']); // Get my total time
});
