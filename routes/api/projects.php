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

    // Kanban board management - Move outside the group to avoid conflicts
    Route::get('/{projectId}/kanban/board', [KanbanController::class, 'getBoard']); // Get kanban board
    Route::get('/{projectId}/kanban/tasks', [KanbanController::class, 'getProjectTasks']); // Get project tasks for kanban
    Route::post('/{projectId}/kanban/columns', [KanbanController::class, 'createColumn']); // Create column
    Route::patch('/{projectId}/kanban/columns/{columnId}', [KanbanController::class, 'updateColumn']); // Update column
    Route::delete('/{projectId}/kanban/columns/{columnId}', [KanbanController::class, 'deleteColumn']); // Delete column
    Route::post('/{projectId}/kanban/reorder', [KanbanController::class, 'reorderColumns']); // Reorder columns
    Route::post('/{projectId}/kanban/initialize', [KanbanController::class, 'initializeDefaultColumns']); // Initialize default columns

    // Test route for kanban tasks
    Route::get('/{projectId}/kanban-tasks', [KanbanController::class, 'getProjectTasks']); // Test route
    
    // Simple test route
    Route::get('/{projectId}/test', function($projectId) {
        return response()->json(['message' => 'Test route works', 'projectId' => $projectId]);
    });
    
    // Test kanban route
    Route::get('/{projectId}/kanban-test', function($projectId) {
        return response()->json(['message' => 'Kanban test route works', 'projectId' => $projectId]);
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
