<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaskComment;
use App\Models\Task;
use App\Models\User;

class TaskCommentsTableSeeder extends Seeder
{
    public function run()
    {
        $taskIds = Task::pluck('id')->all();
        $userIds = User::pluck('id')->all();
        for ($i = 1; $i <= 20; $i++) {
            TaskComment::create([
                'task_id' => $taskIds[array_rand($taskIds)],
                'user_id' => $userIds[array_rand($userIds)],
                'content' => 'Bình luận cho task ' . $i,
            ]);
        }
    }
} 