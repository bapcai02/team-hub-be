<?php

use App\Interfaces\Http\Controllers\RBACController;
use Illuminate\Support\Facades\Route;

Route::prefix('rbac')->group(function () {
    // Roles
    Route::get('/roles', [RBACController::class, 'getRoles']);
    Route::get('/roles/{id}', [RBACController::class, 'getRole']);
    Route::post('/roles', [RBACController::class, 'createRole']);
    Route::put('/roles/{id}', [RBACController::class, 'updateRole']);
    Route::delete('/roles/{id}', [RBACController::class, 'deleteRole']);

    // Permissions
    Route::get('/permissions', [RBACController::class, 'getPermissionsByModule']);
    Route::get('/permissions/module/{module}', [RBACController::class, 'getPermissionsByModuleName']);
    Route::post('/permissions', [RBACController::class, 'createPermission']);
    Route::put('/permissions/{id}', [RBACController::class, 'updatePermission']);
    Route::delete('/permissions/{id}', [RBACController::class, 'deletePermission']);

    // User Roles & Permissions
    Route::post('/users/{userId}/roles', [RBACController::class, 'assignRolesToUser']);
    Route::get('/users/{userId}/permissions', [RBACController::class, 'getUserPermissions']);
    Route::post('/users/{userId}/check-permission', [RBACController::class, 'checkUserPermission']);
    Route::get('/users/role/{roleName}', [RBACController::class, 'getUsersByRole']);

    // Audit Logs
    Route::get('/audit-logs', [RBACController::class, 'getAuditLogs']);

    // Modules
    Route::get('/modules', [RBACController::class, 'getAvailableModules']);
}); 