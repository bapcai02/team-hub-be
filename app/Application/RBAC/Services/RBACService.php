<?php

namespace App\Application\RBAC\Services;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RBACService
{
    /**
     * Get all roles with permissions
     */
    public function getAllRoles(): Collection
    {
        return Role::with('permissions')->get();
    }

    /**
     * Get role by ID with permissions
     */
    public function getRoleById(int $id): ?Role
    {
        return Role::with('permissions')->find($id);
    }

    /**
     * Create new role
     */
    public function createRole(array $data): Role
    {
        $role = Role::create($data);

        if (isset($data['permissions'])) {
            $role->assignPermissions($data['permissions']);
        }

        $this->logAudit('create', 'roles', $role->id, [
            'role_name' => $role->name,
            'permissions' => $data['permissions'] ?? []
        ]);

        return $role;
    }

    /**
     * Update role
     */
    public function updateRole(int $id, array $data): Role
    {
        $role = Role::findOrFail($id);
        $oldData = $role->toArray();
        
        $role->update($data);

        if (isset($data['permissions'])) {
            $role->assignPermissions($data['permissions']);
        }

        $this->logAudit('update', 'roles', $role->id, [
            'old_data' => $oldData,
            'new_data' => $role->toArray()
        ]);

        return $role;
    }

    /**
     * Delete role
     */
    public function deleteRole(int $id): bool
    {
        $role = Role::findOrFail($id);
        $roleData = $role->toArray();
        
        $deleted = $role->delete();

        if ($deleted) {
            $this->logAudit('delete', 'roles', $id, [
                'role_name' => $roleData['name']
            ]);
        }

        return $deleted;
    }

    /**
     * Get all permissions grouped by module
     */
    public function getPermissionsByModule(): Collection
    {
        return Permission::active()
            ->orderBy('module')
            ->orderBy('name')
            ->get()
            ->groupBy('module');
    }

    /**
     * Get permissions by module
     */
    public function getPermissionsByModuleName(string $module): Collection
    {
        return Permission::active()->byModule($module)->get();
    }

    /**
     * Create new permission
     */
    public function createPermission(array $data): Permission
    {
        $permission = Permission::create($data);

        $this->logAudit('create', 'permissions', $permission->id, [
            'permission_name' => $permission->name,
            'module' => $permission->module
        ]);

        return $permission;
    }

    /**
     * Update permission
     */
    public function updatePermission(int $id, array $data): Permission
    {
        $permission = Permission::findOrFail($id);
        $oldData = $permission->toArray();
        
        $permission->update($data);

        $this->logAudit('update', 'permissions', $permission->id, [
            'old_data' => $oldData,
            'new_data' => $permission->toArray()
        ]);

        return $permission;
    }

    /**
     * Delete permission
     */
    public function deletePermission(int $id): bool
    {
        $permission = Permission::findOrFail($id);
        $permissionData = $permission->toArray();
        
        $deleted = $permission->delete();

        if ($deleted) {
            $this->logAudit('delete', 'permissions', $id, [
                'permission_name' => $permissionData['name']
            ]);
        }

        return $deleted;
    }

    /**
     * Assign roles to user
     */
    public function assignRolesToUser(int $userId, array $roleIds): void
    {
        $user = User::findOrFail($userId);
        $oldRoles = $user->roles->pluck('id')->toArray();
        
        $user->assignRoles($roleIds);

        $this->logAudit('update', 'users', $userId, [
            'old_roles' => $oldRoles,
            'new_roles' => $roleIds
        ]);
    }

    /**
     * Check if user has permission
     */
    public function userHasPermission(int $userId, string $permission): bool
    {
        $user = User::findOrFail($userId);
        return $user->hasPermission($permission);
    }

    /**
     * Check if user has any of the permissions
     */
    public function userHasAnyPermission(int $userId, array $permissions): bool
    {
        $user = User::findOrFail($userId);
        return $user->hasAnyPermission($permissions);
    }

    /**
     * Check if user has all permissions
     */
    public function userHasAllPermissions(int $userId, array $permissions): bool
    {
        $user = User::findOrFail($userId);
        return $user->hasAllPermissions($permissions);
    }

    /**
     * Get user permissions
     */
    public function getUserPermissions(int $userId): Collection
    {
        $user = User::findOrFail($userId);
        return $user->getAllPermissions();
    }

    /**
     * Get users by role
     */
    public function getUsersByRole(string $roleName): Collection
    {
        return User::whereHas('roles', function ($query) use ($roleName) {
            $query->where('name', $roleName);
        })->get();
    }

    /**
     * Get audit logs with filters
     */
    public function getAuditLogs(array $filters = []): Collection
    {
        $query = AuditLog::with('user');

        if (isset($filters['action'])) {
            $query->byAction($filters['action']);
        }

        if (isset($filters['table'])) {
            $query->byTable($filters['table']);
        }

        if (isset($filters['user_id'])) {
            $query->byUser($filters['user_id']);
        }

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->inDateRange($filters['start_date'], $filters['end_date']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Log audit action
     */
    private function logAudit(string $action, string $table, int $targetId, array $data = []): void
    {
        $user = auth()->user();
        
        AuditLog::create([
            'user_id' => $user ? $user->id : 1,
            'action' => $action,
            'target_table' => $table,
            'target_id' => $targetId,
            'data' => $data,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Get available modules
     */
    public function getAvailableModules(): array
    {
        return [
            'dashboard' => 'Dashboard',
            'projects' => 'Projects',
            'tasks' => 'Tasks',
            'employees' => 'Employees',
            'attendance' => 'Attendance',
            'leaves' => 'Leaves',
            'payroll' => 'Payroll',
            'devices' => 'Devices',
            'documents' => 'Documents',
            'chat' => 'Chat',
            'meetings' => 'Meetings',
            'calendar' => 'Calendar',
            'analytics' => 'Analytics',
            'finance' => 'Finance',
            'guests' => 'Guests',
            'holidays' => 'Holidays',
            'settings' => 'Settings',
            'users' => 'Users',
            'roles' => 'Roles',
            'permissions' => 'Permissions',
        ];
    }

    /**
     * Get default permissions for module
     */
    public function getDefaultPermissionsForModule(string $module): array
    {
        $permissions = [
            'view' => "View {$module}",
            'create' => "Create {$module}",
            'edit' => "Edit {$module}",
            'delete' => "Delete {$module}",
            'export' => "Export {$module}",
            'import' => "Import {$module}",
        ];

        return $permissions;
    }
} 