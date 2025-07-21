<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Favorite;
use App\Models\User;
use App\Models\Project;

class FavoritesTableSeeder extends Seeder
{
    public function run()
    {
        $userIds = User::pluck('id')->all();
        $projectIds = Project::pluck('id')->all();
        $types = ['project']; // chỉ seed project, có thể mở rộng thêm task, document, chat nếu cần
        for ($i = 1; $i <= 10; $i++) {
            Favorite::create([
                'user_id' => $userIds[array_rand($userIds)],
                'type' => $types[array_rand($types)],
                'target_id' => $projectIds[array_rand($projectIds)],
            ]);
        }
    }
} 