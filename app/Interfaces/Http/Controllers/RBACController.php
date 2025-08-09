<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\RBAC\Services\RBACService;
use App\Interfaces\Http\Requests\RBAC\StoreRoleRequest;
use App\Interfaces\Http\Requests\RBAC\UpdateRoleRequest;
use App\Interfaces\Http\Requests\RBAC\StorePermissionRequest;
use App\Interfaces\Http\Requests\RBAC\UpdatePermissionRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RBACController
{
    public function __construct(
        private RBACService $rbacService
    ) {}

    /**
     * Get all roles
     */
    public function getRoles(): JsonResponse
    {
        $roles = $this->rbacService->getAllRoles();
        
        return response()->json([
            'success' => true,
            'data' => $roles
        ]);
    }

    /**
     * Get role by ID
     */
    public function getRole(int $id): JsonResponse
    {
        $role = $this->rbacService->getRoleById($id);
        
        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }

    /**
     * Create new role
     */
    public function createRole(StoreRoleRequest $request): JsonResponse
    {
        $role = $this->rbacService->createRole($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Role created successfully',
            'data' => $role
        ], 201);
    }

    /**
     * Update role
     */
    public function updateRole(UpdateRoleRequest $request, int $id): JsonResponse
    {
        $role = $this->rbacService->updateRole($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully',
            'data' => $role
        ]);
    }

    /**
     * Delete role
     */
    public function deleteRole(int $id): JsonResponse
    {
        $deleted = $this->rbacService->deleteRole($id);

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete role'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully'
        ]);
    }

    /**
     * Get permissions by module
     */
    public function getPermissionsByModule(): JsonResponse
    {
        $permissions = $this->rbacService->getPermissionsByModule();

        return response()->json([
            'success' => true,
            'data' => $permissions
        ]);
    }

    /**
     * Get permissions by module name
     */
    public function getPermissionsByModuleName(string $module): JsonResponse
    {
        $permissions = $this->rbacService->getPermissionsByModuleName($module);

        return response()->json([
            'success' => true,
            'data' => $permissions
        ]);
    }

    /**
     * Create new permission
     */
    public function createPermission(StorePermissionRequest $request): JsonResponse
    {
        $permission = $this->rbacService->createPermission($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Permission created successfully',
            'data' => $permission
        ], 201);
    }

    /**
     * Update permission
     */
    public function updatePermission(UpdatePermissionRequest $request, int $id): JsonResponse
    {
        $permission = $this->rbacService->updatePermission($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Permission updated successfully',
            'data' => $permission
        ]);
    }

    /**
     * Delete permission
     */
    public function deletePermission(int $id): JsonResponse
    {
        $deleted = $this->rbacService->deletePermission($id);

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete permission'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Permission deleted successfully'
        ]);
    }

    /**
     * Assign roles to user
     */
    public function assignRolesToUser(Request $request, int $userId): JsonResponse
    {
        $request->validate([
            'role_ids' => 'required|array',
            'role_ids.*' => 'integer|exists:roles,id'
        ]);

        $this->rbacService->assignRolesToUser($userId, $request->role_ids);

        return response()->json([
            'success' => true,
            'message' => 'Roles assigned successfully'
        ]);
    }

    /**
     * Check user permission
     */
    public function checkUserPermission(Request $request, int $userId): JsonResponse
    {
        $request->validate([
            'permission' => 'required|string'
        ]);

        $hasPermission = $this->rbacService->userHasPermission($userId, $request->permission);

        return response()->json([
            'success' => true,
            'data' => [
                'user_id' => $userId,
                'permission' => $request->permission,
                'has_permission' => $hasPermission
            ]
        ]);
    }

    /**
     * Get user permissions
     */
    public function getUserPermissions(int $userId): JsonResponse
    {
        $permissions = $this->rbacService->getUserPermissions($userId);

        return response()->json([
            'success' => true,
            'data' => $permissions
        ]);
    }

    /**
     * Get users by role
     */
    public function getUsersByRole(string $roleName): JsonResponse
    {
        $users = $this->rbacService->getUsersByRole($roleName);

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Get audit logs
     */
    public function getAuditLogs(Request $request): JsonResponse
    {
        $filters = $request->only(['action', 'table', 'user_id', 'start_date', 'end_date']);
        $logs = $this->rbacService->getAuditLogs($filters);

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }

    /**
     * Get available modules
     */
    public function getAvailableModules(): JsonResponse
    {
        $modules = $this->rbacService->getAvailableModules();

        return response()->json([
            'success' => true,
            'data' => $modules
        ]);
    }
} 