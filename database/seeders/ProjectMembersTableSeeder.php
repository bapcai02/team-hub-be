<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectMember;
use App\Models\Project;
use App\Models\User;

class ProjectMembersTableSeeder extends Seeder
{
    public function run()
    {
        $projectIds = Project::pluck('id')->all();
        $userIds = User::pluck('id')->all();
        $roles = ['viewer', 'editor', 'manager'];
        for ($i = 1; $i <= 20; $i++) {
            ProjectMember::create([
                'project_id' => $projectIds[array_rand($projectIds)],
                'user_id' => $userIds[array_rand($userIds)],
                'role' => $roles[array_rand($roles)],
            ]);
        }
    }
} 