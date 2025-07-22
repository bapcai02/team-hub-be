<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaskChecklist;
use App\Models\Task;

class TaskChecklistsTableSeeder extends Seeder
{
    public function run()
    {
        $taskIds = Task::pluck('id')->all();
        for ($i = 1; $i <= 20; $i++) {
            TaskChecklist::create([
                'task_id' => $taskIds[array_rand($taskIds)],
                'title' => 'Checklist ' . $i,
                'is_completed' => rand(0, 1),
            ]);
        }
    }
} 