<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Upload;
use App\Models\User;
use App\Models\Task;

class UploadsTableSeeder extends Seeder
{
    public function run()
    {
        $userIds = User::pluck('id')->all();
        $taskIds = Task::pluck('id')->all();
        $types = ['task', 'message', 'document'];
        for ($i = 1; $i <= 10; $i++) {
            $type = $types[array_rand($types)];
            $related_id = $type === 'task' ? ($taskIds[array_rand($taskIds)] ?? 1) : 1;
            Upload::create([
                'uploaded_by' => $userIds[array_rand($userIds)],
                'file_path' => 'uploads/file' . $i . '.txt',
                'original_name' => 'file' . $i . '.txt',
                'mime_type' => 'text/plain',
                'size' => rand(1000, 100000),
                'related_type' => $type,
                'related_id' => $related_id,
            ]);
        }
    }
} 