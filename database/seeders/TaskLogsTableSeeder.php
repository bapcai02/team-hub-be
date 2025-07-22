<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaskLog;
use App\Models\Task;
use App\Models\User;

class TaskLogsTableSeeder extends Seeder
{
    public function run()
    {
        $taskIds = Task::pluck('id')->all();
        $userIds = User::pluck('id')->all();
        for ($i = 1; $i <= 10; $i++) {
            TaskLog::create([
                'task_id' => $taskIds[array_rand($taskIds)],
                'user_id' => $userIds[array_rand($userIds)],
                'start_time' => now()->subHours(rand(1, 100)),
                'end_time' => now()->addHours(rand(1, 100)),
                'duration' => rand(1, 8) * 60,
            ]);
        }
    }
} 