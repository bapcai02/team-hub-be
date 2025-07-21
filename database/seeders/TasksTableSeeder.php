<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;

class TasksTableSeeder extends Seeder
{
    public function run()
    {
        $projectIds = Project::pluck('id')->all();
        $userIds = User::pluck('id')->all();
        $statuses = ['todo', 'in_progress', 'done', 'backlog'];
        $priorities = ['low', 'medium', 'high'];
        for ($i = 1; $i <= 20; $i++) {
            Task::create([
                'project_id' => $projectIds[array_rand($projectIds)],
                'assigned_to' => $userIds[array_rand($userIds)],
                'title' => 'Task ' . $i,
                'description' => 'Mô tả task ' . $i,
                'status' => $statuses[array_rand($statuses)],
                'priority' => $priorities[array_rand($priorities)],
                'deadline' => now()->addDays(rand(1, 30)),
                'created_by' => $userIds[array_rand($userIds)],
            ]);
        }
    }
} 