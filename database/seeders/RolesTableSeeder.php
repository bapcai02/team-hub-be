<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        // Create default roles
        $roles = [
            [
                'name' => 'Super Admin',
                'description' => 'Full system administrator with all permissions',
                'is_active' => true,
            ],
            [
                'name' => 'Admin',
                'description' => 'System administrator with most permissions',
                'is_active' => true,
            ],
            [
                'name' => 'Manager',
                'description' => 'Department manager with team management permissions',
                'is_active' => true,
            ],
            [
                'name' => 'Team Lead',
                'description' => 'Team leader with project and task management permissions',
                'is_active' => true,
            ],
            [
                'name' => 'Developer',
                'description' => 'Software developer with project and task permissions',
                'is_active' => true,
            ],
            [
                'name' => 'HR Manager',
                'description' => 'Human resources manager with employee management permissions',
                'is_active' => true,
            ],
            [
                'name' => 'Finance Manager',
                'description' => 'Finance manager with payroll and expense permissions',
                'is_active' => true,
            ],
            [
                'name' => 'User',
                'description' => 'Regular user with basic permissions',
                'is_active' => true,
            ],
            [
                'name' => 'Guest',
                'description' => 'Guest user with limited view permissions',
                'is_active' => true,
            ],
        ];

        foreach ($roles as $roleData) {
            $role = Role::create($roleData);
            
            // Assign permissions based on role
            $this->assignPermissionsToRole($role);
        }
    }

    private function assignPermissionsToRole(Role $role)
    {
        $permissions = Permission::all();
        
        switch ($role->name) {
            case 'Super Admin':
                // All permissions
                $role->assignPermissions($permissions->pluck('id')->toArray());
                break;
                
            case 'Admin':
                // Most permissions except some sensitive ones
                $adminPermissions = $permissions->filter(function ($permission) {
                    return !in_array($permission->name, [
                        'delete_users',
                        'delete_roles',
                        'delete_permissions',
                        'create_roles',
                        'create_permissions',
                    ]);
                });
                $role->assignPermissions($adminPermissions->pluck('id')->toArray());
                break;
                
            case 'Manager':
                // Project and team management permissions
                $managerPermissions = $permissions->filter(function ($permission) {
                    return in_array($permission->module, [
                        'dashboard',
                        'projects',
                        'tasks',
                        'employees',
                        'attendance',
                        'leaves',
                        'chat',
                        'meetings',
                        'calendar',
                        'documents',
                        'analytics',
                    ]);
                });
                $role->assignPermissions($managerPermissions->pluck('id')->toArray());
                break;
                
            case 'Team Lead':
                // Project and task management permissions
                $teamLeadPermissions = $permissions->filter(function ($permission) {
                    return in_array($permission->module, [
                        'dashboard',
                        'projects',
                        'tasks',
                        'chat',
                        'meetings',
                        'calendar',
                        'documents',
                    ]);
                });
                $role->assignPermissions($teamLeadPermissions->pluck('id')->toArray());
                break;
                
            case 'Developer':
                // Project and task permissions
                $developerPermissions = $permissions->filter(function ($permission) {
                    return in_array($permission->module, [
                        'dashboard',
                        'projects',
                        'tasks',
                        'chat',
                        'meetings',
                        'calendar',
                        'documents',
                    ]) && !in_array($permission->name, [
                        'delete_projects',
                        'delete_tasks',
                    ]);
                });
                $role->assignPermissions($developerPermissions->pluck('id')->toArray());
                break;
                
            case 'HR Manager':
                // HR-related permissions
                $hrPermissions = $permissions->filter(function ($permission) {
                    return in_array($permission->module, [
                        'dashboard',
                        'employees',
                        'attendance',
                        'leaves',
                        'analytics',
                    ]);
                });
                $role->assignPermissions($hrPermissions->pluck('id')->toArray());
                break;
                
            case 'Finance Manager':
                // Finance-related permissions
                $financePermissions = $permissions->filter(function ($permission) {
                    return in_array($permission->module, [
                        'dashboard',
                        'payroll',
                        'finance',
                        'analytics',
                    ]);
                });
                $role->assignPermissions($financePermissions->pluck('id')->toArray());
                break;
                
            case 'User':
                // Basic permissions
                $userPermissions = $permissions->filter(function ($permission) {
                    return in_array($permission->module, [
                        'dashboard',
                        'projects',
                        'tasks',
                        'chat',
                        'meetings',
                        'calendar',
                        'documents',
                    ]) && in_array($permission->name, [
                        'view_dashboard',
                        'view_projects',
                        'view_tasks',
                        'view_chat',
                        'send_messages',
                        'view_meetings',
                        'view_calendar',
                        'view_documents',
                    ]);
                });
                $role->assignPermissions($userPermissions->pluck('id')->toArray());
                break;
                
            case 'Guest':
                // Limited view permissions
                $guestPermissions = $permissions->filter(function ($permission) {
                    return in_array($permission->name, [
                        'view_dashboard',
                        'view_projects',
                        'view_tasks',
                        'view_chat',
                        'view_meetings',
                        'view_calendar',
                        'view_documents',
                    ]);
                });
                $role->assignPermissions($guestPermissions->pluck('id')->toArray());
                break;
        }
    }
} 