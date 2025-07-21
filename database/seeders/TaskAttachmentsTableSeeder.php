<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaskAttachment;
use App\Models\Task;
use App\Models\User;

class TaskAttachmentsTableSeeder extends Seeder
{
    public function run()
    {
        $taskIds = Task::pluck('id')->all();
        $userIds = User::pluck('id')->all();
        for ($i = 1; $i <= 10; $i++) {
            TaskAttachment::create([
                'task_id' => $taskIds[array_rand($taskIds)],
                'uploaded_by' => $userIds[array_rand($userIds)],
                'file_path' => 'attachments/task' . $i . '.txt',
                'uploaded_at' => now()->subDays(rand(0, 30)),
            ]);
        }
    }
} 