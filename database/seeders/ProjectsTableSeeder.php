<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectsTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            Project::create([
                'name' => 'Project ' . $i,
                'description' => 'Mô tả dự án ' . $i,
                'owner_id' => 1,
                'start_date' => now()->subDays(rand(1, 100)),
                'end_date' => now()->addDays(rand(1, 100)),
                'status' => 'active',
            ]);
        }
    }
} 