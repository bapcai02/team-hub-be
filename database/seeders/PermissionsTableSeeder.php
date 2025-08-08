<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // Dashboard
            ['name' => 'view_dashboard', 'description' => 'View dashboard', 'module' => 'dashboard', 'is_active' => true],
            
            // Projects
            ['name' => 'view_projects', 'description' => 'View projects', 'module' => 'projects', 'is_active' => true],
            ['name' => 'create_projects', 'description' => 'Create projects', 'module' => 'projects', 'is_active' => true],
            ['name' => 'edit_projects', 'description' => 'Edit projects', 'module' => 'projects', 'is_active' => true],
            ['name' => 'delete_projects', 'description' => 'Delete projects', 'module' => 'projects', 'is_active' => true],
            
            // Tasks
            ['name' => 'view_tasks', 'description' => 'View tasks', 'module' => 'tasks', 'is_active' => true],
            ['name' => 'create_tasks', 'description' => 'Create tasks', 'module' => 'tasks', 'is_active' => true],
            ['name' => 'edit_tasks', 'description' => 'Edit tasks', 'module' => 'tasks', 'is_active' => true],
            ['name' => 'delete_tasks', 'description' => 'Delete tasks', 'module' => 'tasks', 'is_active' => true],
            
            // Employees
            ['name' => 'view_employees', 'description' => 'View employees', 'module' => 'employees', 'is_active' => true],
            ['name' => 'create_employees', 'description' => 'Create employees', 'module' => 'employees', 'is_active' => true],
            ['name' => 'edit_employees', 'description' => 'Edit employees', 'module' => 'employees', 'is_active' => true],
            ['name' => 'delete_employees', 'description' => 'Delete employees', 'module' => 'employees', 'is_active' => true],
            
            // Attendance
            ['name' => 'view_attendance', 'description' => 'View attendance', 'module' => 'attendance', 'is_active' => true],
            ['name' => 'create_attendance', 'description' => 'Create attendance', 'module' => 'attendance', 'is_active' => true],
            ['name' => 'edit_attendance', 'description' => 'Edit attendance', 'module' => 'attendance', 'is_active' => true],
            ['name' => 'delete_attendance', 'description' => 'Delete attendance', 'module' => 'attendance', 'is_active' => true],
            
            // Leaves
            ['name' => 'view_leaves', 'description' => 'View leaves', 'module' => 'leaves', 'is_active' => true],
            ['name' => 'create_leaves', 'description' => 'Create leaves', 'module' => 'leaves', 'is_active' => true],
            ['name' => 'edit_leaves', 'description' => 'Edit leaves', 'module' => 'leaves', 'is_active' => true],
            ['name' => 'delete_leaves', 'description' => 'Delete leaves', 'module' => 'leaves', 'is_active' => true],
            ['name' => 'approve_leaves', 'description' => 'Approve leaves', 'module' => 'leaves', 'is_active' => true],
            
            // Payroll
            ['name' => 'view_payroll', 'description' => 'View payroll', 'module' => 'payroll', 'is_active' => true],
            ['name' => 'create_payroll', 'description' => 'Create payroll', 'module' => 'payroll', 'is_active' => true],
            ['name' => 'edit_payroll', 'description' => 'Edit payroll', 'module' => 'payroll', 'is_active' => true],
            ['name' => 'delete_payroll', 'description' => 'Delete payroll', 'module' => 'payroll', 'is_active' => true],
            
            // Devices
            ['name' => 'view_devices', 'description' => 'View devices', 'module' => 'devices', 'is_active' => true],
            ['name' => 'create_devices', 'description' => 'Create devices', 'module' => 'devices', 'is_active' => true],
            ['name' => 'edit_devices', 'description' => 'Edit devices', 'module' => 'devices', 'is_active' => true],
            ['name' => 'delete_devices', 'description' => 'Delete devices', 'module' => 'devices', 'is_active' => true],
            
            // Documents
            ['name' => 'view_documents', 'description' => 'View documents', 'module' => 'documents', 'is_active' => true],
            ['name' => 'create_documents', 'description' => 'Create documents', 'module' => 'documents', 'is_active' => true],
            ['name' => 'edit_documents', 'description' => 'Edit documents', 'module' => 'documents', 'is_active' => true],
            ['name' => 'delete_documents', 'description' => 'Delete documents', 'module' => 'documents', 'is_active' => true],
            
            // Chat
            ['name' => 'view_chat', 'description' => 'View chat', 'module' => 'chat', 'is_active' => true],
            ['name' => 'send_messages', 'description' => 'Send messages', 'module' => 'chat', 'is_active' => true],
            ['name' => 'delete_messages', 'description' => 'Delete messages', 'module' => 'chat', 'is_active' => true],
            
            // Meetings
            ['name' => 'view_meetings', 'description' => 'View meetings', 'module' => 'meetings', 'is_active' => true],
            ['name' => 'create_meetings', 'description' => 'Create meetings', 'module' => 'meetings', 'is_active' => true],
            ['name' => 'edit_meetings', 'description' => 'Edit meetings', 'module' => 'meetings', 'is_active' => true],
            ['name' => 'delete_meetings', 'description' => 'Delete meetings', 'module' => 'meetings', 'is_active' => true],
            
            // Calendar
            ['name' => 'view_calendar', 'description' => 'View calendar', 'module' => 'calendar', 'is_active' => true],
            ['name' => 'create_events', 'description' => 'Create events', 'module' => 'calendar', 'is_active' => true],
            ['name' => 'edit_events', 'description' => 'Edit events', 'module' => 'calendar', 'is_active' => true],
            ['name' => 'delete_events', 'description' => 'Delete events', 'module' => 'calendar', 'is_active' => true],
            
            // Analytics
            ['name' => 'view_analytics', 'description' => 'View analytics', 'module' => 'analytics', 'is_active' => true],
            ['name' => 'export_analytics', 'description' => 'Export analytics', 'module' => 'analytics', 'is_active' => true],
            
            // Finance
            ['name' => 'view_finance', 'description' => 'View finance', 'module' => 'finance', 'is_active' => true],
            ['name' => 'create_finance', 'description' => 'Create finance records', 'module' => 'finance', 'is_active' => true],
            ['name' => 'edit_finance', 'description' => 'Edit finance records', 'module' => 'finance', 'is_active' => true],
            ['name' => 'delete_finance', 'description' => 'Delete finance records', 'module' => 'finance', 'is_active' => true],
            
            // Guests
            ['name' => 'view_guests', 'description' => 'View guests', 'module' => 'guests', 'is_active' => true],
            ['name' => 'create_guests', 'description' => 'Create guests', 'module' => 'guests', 'is_active' => true],
            ['name' => 'edit_guests', 'description' => 'Edit guests', 'module' => 'guests', 'is_active' => true],
            ['name' => 'delete_guests', 'description' => 'Delete guests', 'module' => 'guests', 'is_active' => true],
            
            // Holidays
            ['name' => 'view_holidays', 'description' => 'View holidays', 'module' => 'holidays', 'is_active' => true],
            ['name' => 'create_holidays', 'description' => 'Create holidays', 'module' => 'holidays', 'is_active' => true],
            ['name' => 'edit_holidays', 'description' => 'Edit holidays', 'module' => 'holidays', 'is_active' => true],
            ['name' => 'delete_holidays', 'description' => 'Delete holidays', 'module' => 'holidays', 'is_active' => true],
            
            // Settings
            ['name' => 'view_settings', 'description' => 'View settings', 'module' => 'settings', 'is_active' => true],
            ['name' => 'edit_settings', 'description' => 'Edit settings', 'module' => 'settings', 'is_active' => true],
            
            // Users
            ['name' => 'view_users', 'description' => 'View users', 'module' => 'users', 'is_active' => true],
            ['name' => 'create_users', 'description' => 'Create users', 'module' => 'users', 'is_active' => true],
            ['name' => 'edit_users', 'description' => 'Edit users', 'module' => 'users', 'is_active' => true],
            ['name' => 'delete_users', 'description' => 'Delete users', 'module' => 'users', 'is_active' => true],
            
            // Roles
            ['name' => 'view_roles', 'description' => 'View roles', 'module' => 'roles', 'is_active' => true],
            ['name' => 'create_roles', 'description' => 'Create roles', 'module' => 'roles', 'is_active' => true],
            ['name' => 'edit_roles', 'description' => 'Edit roles', 'module' => 'roles', 'is_active' => true],
            ['name' => 'delete_roles', 'description' => 'Delete roles', 'module' => 'roles', 'is_active' => true],
            
            // Permissions
            ['name' => 'view_permissions', 'description' => 'View permissions', 'module' => 'permissions', 'is_active' => true],
            ['name' => 'create_permissions', 'description' => 'Create permissions', 'module' => 'permissions', 'is_active' => true],
            ['name' => 'edit_permissions', 'description' => 'Edit permissions', 'module' => 'permissions', 'is_active' => true],
            ['name' => 'delete_permissions', 'description' => 'Delete permissions', 'module' => 'permissions', 'is_active' => true],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
} 