<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domain\Notification\Entities\NotificationTemplate;

class NotificationTemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'project_assignment',
                'category' => 'project',
                'type' => 'in_app',
                'title_template' => 'New Project Assignment: {{project_name}}',
                'message_template' => 'You have been assigned to the project "{{project_name}}" with role "{{role}}". Start date: {{start_date}}.',
                'variables' => [
                    ['key' => 'project_name', 'label' => 'Project Name', 'required' => true],
                    ['key' => 'role', 'label' => 'Role', 'required' => true],
                    ['key' => 'start_date', 'label' => 'Start Date', 'required' => true],
                ],
                'channels' => ['in_app', 'email'],
                'priority' => 'normal',
                'is_active' => true,
            ],
            [
                'name' => 'task_due_reminder',
                'category' => 'project',
                'type' => 'in_app',
                'title_template' => 'Task Due Reminder: {{task_name}}',
                'message_template' => 'The task "{{task_name}}" is due on {{due_date}}. Please complete it on time.',
                'variables' => [
                    ['key' => 'task_name', 'label' => 'Task Name', 'required' => true],
                    ['key' => 'due_date', 'label' => 'Due Date', 'required' => true],
                ],
                'channels' => ['in_app', 'email', 'push'],
                'priority' => 'high',
                'is_active' => true,
            ],
            [
                'name' => 'contract_expiry',
                'category' => 'contract',
                'type' => 'email',
                'title_template' => 'Contract Expiry Notice: {{contract_name}}',
                'message_template' => 'The contract "{{contract_name}}" will expire on {{expiry_date}}. Please review and take necessary action.',
                'variables' => [
                    ['key' => 'contract_name', 'label' => 'Contract Name', 'required' => true],
                    ['key' => 'expiry_date', 'label' => 'Expiry Date', 'required' => true],
                ],
                'channels' => ['email', 'in_app'],
                'priority' => 'high',
                'is_active' => true,
            ],
            [
                'name' => 'system_maintenance',
                'category' => 'system',
                'type' => 'in_app',
                'title_template' => 'System Maintenance: {{maintenance_type}}',
                'message_template' => 'System maintenance is scheduled for {{start_time}} to {{end_time}}. Service may be temporarily unavailable.',
                'variables' => [
                    ['key' => 'maintenance_type', 'label' => 'Maintenance Type', 'required' => true],
                    ['key' => 'start_time', 'label' => 'Start Time', 'required' => true],
                    ['key' => 'end_time', 'label' => 'End Time', 'required' => true],
                ],
                'channels' => ['in_app', 'email'],
                'priority' => 'normal',
                'is_active' => true,
            ],
            [
                'name' => 'leave_approval',
                'category' => 'hr',
                'type' => 'in_app',
                'title_template' => 'Leave Request {{status}}: {{leave_type}}',
                'message_template' => 'Your leave request for {{leave_type}} from {{start_date}} to {{end_date}} has been {{status}}.',
                'variables' => [
                    ['key' => 'status', 'label' => 'Status', 'required' => true],
                    ['key' => 'leave_type', 'label' => 'Leave Type', 'required' => true],
                    ['key' => 'start_date', 'label' => 'Start Date', 'required' => true],
                    ['key' => 'end_date', 'label' => 'End Date', 'required' => true],
                ],
                'channels' => ['in_app', 'email'],
                'priority' => 'normal',
                'is_active' => true,
            ],
            [
                'name' => 'device_alert',
                'category' => 'device',
                'type' => 'push',
                'title_template' => 'Device Alert: {{device_name}}',
                'message_template' => 'The device "{{device_name}}" has reported an issue: {{issue_description}}. Please check immediately.',
                'variables' => [
                    ['key' => 'device_name', 'label' => 'Device Name', 'required' => true],
                    ['key' => 'issue_description', 'label' => 'Issue Description', 'required' => true],
                ],
                'channels' => ['push', 'in_app', 'email'],
                'priority' => 'urgent',
                'is_active' => true,
            ],
            [
                'name' => 'financial_report',
                'category' => 'finance',
                'type' => 'email',
                'title_template' => 'Financial Report: {{report_type}}',
                'message_template' => 'The {{report_type}} financial report for {{period}} is now available. Please review and take action if needed.',
                'variables' => [
                    ['key' => 'report_type', 'label' => 'Report Type', 'required' => true],
                    ['key' => 'period', 'label' => 'Period', 'required' => true],
                ],
                'channels' => ['email', 'in_app'],
                'priority' => 'normal',
                'is_active' => true,
            ],
        ];

        foreach ($templates as $template) {
            NotificationTemplate::create($template);
        }
    }
}
