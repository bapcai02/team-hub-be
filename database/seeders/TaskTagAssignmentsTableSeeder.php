<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaskTagAssignment;
use App\Models\Task;
use App\Models\TaskTag;

class TaskTagAssignmentsTableSeeder extends Seeder
{
    public function run()
    {
        $taskIds = Task::pluck('id')->all();
        $tagIds = TaskTag::pluck('id')->all();
        for ($i = 1; $i <= 20; $i++) {
            TaskTagAssignment::create([
                'task_id' => $taskIds[array_rand($taskIds)],
                'task_tag_id' => $tagIds[array_rand($tagIds)],
            ]);
        }
    }
} 