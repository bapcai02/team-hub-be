<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Document;
use App\Models\ProjectEditHistory;
use App\Models\ProjectMember;
use App\Models\TaskAttachment;
use App\Models\User;

class ProjectDataSeeder extends Seeder
{
    public function run(): void
    {
        $projectId = 12;
        
        // Thêm tasks cho project
        $tasks = [
            [
                'project_id' => $projectId,
                'title' => 'Thiết kế giao diện',
                'description' => 'Thiết kế giao diện người dùng cho ứng dụng',
                'status' => 'in_progress',
                'priority' => 'high',
                'assigned_to' => 1,
                'deadline' => now()->addDays(7)->toDateString(),
                'created_by' => 1,
            ],
            [
                'project_id' => $projectId,
                'title' => 'Phát triển API',
                'description' => 'Xây dựng các API endpoints cho backend',
                'status' => 'todo',
                'priority' => 'medium',
                'assigned_to' => 2,
                'deadline' => now()->addDays(14)->toDateString(),
                'created_by' => 1,
            ],
            [
                'project_id' => $projectId,
                'title' => 'Testing và QA',
                'description' => 'Kiểm thử và đảm bảo chất lượng',
                'status' => 'done',
                'priority' => 'low',
                'assigned_to' => 3,
                'deadline' => now()->addDays(21)->toDateString(),
                'created_by' => 1,
            ],
            [
                'project_id' => $projectId,
                'title' => 'Deploy production',
                'description' => 'Triển khai lên môi trường production',
                'status' => 'todo',
                'priority' => 'high',
                'assigned_to' => 1,
                'deadline' => now()->addDays(30)->toDateString(),
                'created_by' => 1,
            ]
        ];

        foreach ($tasks as $taskData) {
            Task::create($taskData);
        }

        // Thêm documents cho project
        $documents = [
            [
                'title' => 'Tài liệu thiết kế',
                'parent_id' => null,
                'created_by' => 1,
                'visibility' => 'public',
            ],
            [
                'title' => 'API Documentation',
                'parent_id' => null,
                'created_by' => 2,
                'visibility' => 'public',
            ],
            [
                'title' => 'User Manual',
                'parent_id' => null,
                'created_by' => 1,
                'visibility' => 'public',
            ]
        ];

        foreach ($documents as $docData) {
            Document::create($docData);
        }

        // Thêm task attachments cho project
        $taskAttachments = [
            [
                'task_id' => 1, // Task đầu tiên
                'file_path' => 'uploads/task_attachments/design_mockup.png',
                'uploaded_by' => 1,
                'uploaded_at' => now(),
            ],
            [
                'task_id' => 1,
                'file_path' => 'uploads/task_attachments/design_specs.pdf',
                'uploaded_by' => 1,
                'uploaded_at' => now(),
            ],
            [
                'task_id' => 2, // Task thứ hai
                'file_path' => 'uploads/task_attachments/api_specs.json',
                'uploaded_by' => 2,
                'uploaded_at' => now(),
            ],
            [
                'task_id' => 3, // Task thứ ba
                'file_path' => 'uploads/task_attachments/test_results.xlsx',
                'uploaded_by' => 3,
                'uploaded_at' => now(),
            ]
        ];

        foreach ($taskAttachments as $attachmentData) {
            \App\Models\TaskAttachment::create($attachmentData);
        }

        // Thêm edit history cho project
        $editHistory = [
            [
                'project_id' => $projectId,
                'user_id' => 1,
                'changes' => json_encode(['message' => 'Project created']),
            ],
            [
                'project_id' => $projectId,
                'user_id' => 1,
                'changes' => json_encode(['name' => 'Updated project name', 'description' => 'Updated description']),
            ],
            [
                'project_id' => $projectId,
                'user_id' => 2,
                'changes' => json_encode(['status' => 'active', 'progress' => 25]),
            ]
        ];

        foreach ($editHistory as $historyData) {
            ProjectEditHistory::create($historyData);
        }

        // Thêm project members (nếu chưa có)
        $members = [
            ['project_id' => $projectId, 'user_id' => 1, 'role' => 'manager'],
            ['project_id' => $projectId, 'user_id' => 2, 'role' => 'editor'],
            ['project_id' => $projectId, 'user_id' => 3, 'role' => 'viewer'],
        ];

        foreach ($members as $memberData) {
            // Kiểm tra xem đã tồn tại chưa
            $exists = ProjectMember::where('project_id', $memberData['project_id'])
                ->where('user_id', $memberData['user_id'])
                ->exists();
            
            if (!$exists) {
                ProjectMember::create($memberData);
            }
        }

        echo "Đã thêm dữ liệu mẫu cho project ID = {$projectId}\n";
        echo "- Tasks: " . count($tasks) . " items\n";
        echo "- Documents: " . count($documents) . " items\n";
        echo "- Task Attachments: " . count($taskAttachments) . " items\n";
        echo "- Edit History: " . count($editHistory) . " items\n";
        echo "- Members: " . count($members) . " items\n";
    }
} 