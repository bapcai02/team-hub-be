<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class RBACDataSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('=== RBAC Data Summary ===');
        
        // Count roles
        $roleCount = Role::count();
        $this->command->info("Roles: {$roleCount}");
        
        // Count permissions
        $permissionCount = Permission::count();
        $this->command->info("Permissions: {$permissionCount}");
        
        // Count users
        $userCount = User::count();
        $this->command->info("Users: {$userCount}");
        
        // Count role-user assignments
        $roleUserCount = DB::table('role_user')->count();
        $this->command->info("Role-User Assignments: {$roleUserCount}");
        
        // Count permission-role assignments
        $permissionRoleCount = DB::table('permission_role')->count();
        $this->command->info("Permission-Role Assignments: {$permissionRoleCount}");
        
        // Count audit logs
        $auditLogCount = AuditLog::count();
        $this->command->info("Audit Logs: {$auditLogCount}");
        
        // Show role details
        $this->command->info("\n=== Role Details ===");
        $roles = Role::with('permissions')->get();
        foreach ($roles as $role) {
            $permissionCount = $role->permissions->count();
            $userCount = $role->users->count();
            $this->command->info("Role: {$role->name} - Permissions: {$permissionCount}, Users: {$userCount}");
        }
        
        // Show user-role assignments
        $this->command->info("\n=== User-Role Assignments ===");
        $users = User::with('roles')->get();
        foreach ($users as $user) {
            $roleNames = $user->roles->pluck('name')->implode(', ');
            $this->command->info("User: {$user->name} - Roles: {$roleNames}");
        }
        
        // Show recent audit logs
        $this->command->info("\n=== Recent Audit Logs ===");
        $recentLogs = AuditLog::with('user')->latest()->take(5)->get();
        foreach ($recentLogs as $log) {
            $this->command->info("Action: {$log->action} on {$log->target_table} by {$log->user->name}");
        }
        
        $this->command->info("\n=== RBAC System Ready! ===");
    }
} 