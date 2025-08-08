<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class AuditLogsTableSeeder extends Seeder
{
    public function run()
    {
        // Get some users for audit logs
        $users = User::take(3)->get();
        if ($users->isEmpty()) {
            // Create a default user if none exist
            $users = collect([User::create([
                'name' => 'System Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ])]);
        }

        $auditLogs = [
            // Role management activities
            [
                'user_id' => $users->first()->id,
                'action' => 'create',
                'target_table' => 'roles',
                'target_id' => 1,
                'data' => [
                    'role_name' => 'Super Admin',
                    'description' => 'Full system administrator with all permissions',
                    'permissions' => [1, 2, 3, 4, 5]
                ],
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ],
            [
                'user_id' => $users->first()->id,
                'action' => 'create',
                'target_table' => 'roles',
                'target_id' => 2,
                'data' => [
                    'role_name' => 'Admin',
                    'description' => 'System administrator with most permissions',
                    'permissions' => [1, 2, 3, 4]
                ],
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ],
            [
                'user_id' => $users->first()->id,
                'action' => 'update',
                'target_table' => 'roles',
                'target_id' => 1,
                'data' => [
                    'old_data' => ['name' => 'Super Admin', 'description' => 'Old description'],
                    'new_data' => ['name' => 'Super Admin', 'description' => 'Full system administrator with all permissions']
                ],
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ],

            // Permission management activities
            [
                'user_id' => $users->first()->id,
                'action' => 'create',
                'target_table' => 'permissions',
                'target_id' => 1,
                'data' => [
                    'permission_name' => 'view_dashboard',
                    'module' => 'dashboard',
                    'description' => 'View dashboard'
                ],
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ],
            [
                'user_id' => $users->first()->id,
                'action' => 'create',
                'target_table' => 'permissions',
                'target_id' => 2,
                'data' => [
                    'permission_name' => 'view_projects',
                    'module' => 'projects',
                    'description' => 'View projects'
                ],
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ],

            // User management activities
            [
                'user_id' => $users->first()->id,
                'action' => 'create',
                'target_table' => 'users',
                'target_id' => 2,
                'data' => [
                    'user_name' => 'John Doe',
                    'email' => 'john@example.com',
                    'roles' => [2, 3]
                ],
                'ip_address' => '192.168.1.101',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
            ],
            [
                'user_id' => $users->first()->id,
                'action' => 'update',
                'target_table' => 'users',
                'target_id' => 2,
                'data' => [
                    'old_roles' => [2],
                    'new_roles' => [2, 3]
                ],
                'ip_address' => '192.168.1.101',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
            ],

            // Project management activities
            [
                'user_id' => $users->first()->id,
                'action' => 'create',
                'target_table' => 'projects',
                'target_id' => 1,
                'data' => [
                    'project_name' => 'Website Redesign',
                    'description' => 'Redesign company website',
                    'status' => 'active'
                ],
                'ip_address' => '192.168.1.102',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36',
            ],
            [
                'user_id' => $users->first()->id,
                'action' => 'update',
                'target_table' => 'projects',
                'target_id' => 1,
                'data' => [
                    'old_data' => ['status' => 'active'],
                    'new_data' => ['status' => 'completed']
                ],
                'ip_address' => '192.168.1.102',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36',
            ],

            // Task management activities
            [
                'user_id' => $users->first()->id,
                'action' => 'create',
                'target_table' => 'tasks',
                'target_id' => 1,
                'data' => [
                    'task_name' => 'Design Homepage',
                    'description' => 'Create new homepage design',
                    'project_id' => 1,
                    'status' => 'pending'
                ],
                'ip_address' => '192.168.1.102',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36',
            ],
            [
                'user_id' => $users->first()->id,
                'action' => 'update',
                'target_table' => 'tasks',
                'target_id' => 1,
                'data' => [
                    'old_data' => ['status' => 'pending'],
                    'new_data' => ['status' => 'in_progress']
                ],
                'ip_address' => '192.168.1.102',
                'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36',
            ],

            // Employee management activities
            [
                'user_id' => $users->first()->id,
                'action' => 'create',
                'target_table' => 'employees',
                'target_id' => 1,
                'data' => [
                    'employee_name' => 'Jane Smith',
                    'position' => 'Senior Developer',
                    'department' => 'Engineering',
                    'salary' => 75000
                ],
                'ip_address' => '192.168.1.103',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ],

            // Login activities
            [
                'user_id' => $users->first()->id,
                'action' => 'login',
                'target_table' => 'users',
                'target_id' => $users->first()->id,
                'data' => [
                    'login_time' => now()->toISOString(),
                    'session_id' => 'session_' . uniqid()
                ],
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ],
            [
                'user_id' => $users->first()->id,
                'action' => 'logout',
                'target_table' => 'users',
                'target_id' => $users->first()->id,
                'data' => [
                    'logout_time' => now()->toISOString(),
                    'session_duration' => '2 hours 15 minutes'
                ],
                'ip_address' => '192.168.1.100',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ],

            // Document management activities
            [
                'user_id' => $users->first()->id,
                'action' => 'create',
                'target_table' => 'documents',
                'target_id' => 1,
                'data' => [
                    'document_name' => 'Project Requirements',
                    'file_type' => 'pdf',
                    'file_size' => '2.5 MB',
                    'category' => 'Project'
                ],
                'ip_address' => '192.168.1.104',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
            ],
            [
                'user_id' => $users->first()->id,
                'action' => 'delete',
                'target_table' => 'documents',
                'target_id' => 1,
                'data' => [
                    'document_name' => 'Project Requirements',
                    'reason' => 'Outdated document'
                ],
                'ip_address' => '192.168.1.104',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36',
            ],

            // Meeting activities
            [
                'user_id' => $users->first()->id,
                'action' => 'create',
                'target_table' => 'meetings',
                'target_id' => 1,
                'data' => [
                    'meeting_title' => 'Weekly Team Meeting',
                    'date' => '2025-01-15',
                    'time' => '10:00 AM',
                    'participants' => ['John Doe', 'Jane Smith', 'Mike Johnson']
                ],
                'ip_address' => '192.168.1.105',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ],

            // Payroll activities
            [
                'user_id' => $users->first()->id,
                'action' => 'create',
                'target_table' => 'payrolls',
                'target_id' => 1,
                'data' => [
                    'employee_id' => 1,
                    'month' => '2025-01',
                    'base_salary' => 75000,
                    'net_salary' => 65000
                ],
                'ip_address' => '192.168.1.106',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ],
        ];

        foreach ($auditLogs as $logData) {
            AuditLog::create($logData);
        }
    }
} 