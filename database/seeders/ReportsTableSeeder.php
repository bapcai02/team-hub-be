<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\User;

class ReportsTableSeeder extends Seeder
{
    public function run()
    {
        $userIds = User::pluck('id')->all();
        $types = ['project', 'task', 'time', 'employee'];
        for ($i = 1; $i <= 10; $i++) {
            Report::create([
                'title' => 'Report ' . $i,
                'type' => $types[array_rand($types)],
                'filters' => null,
                'generated_by' => $userIds[array_rand($userIds)],
                'generated_at' => now()->subDays(rand(1, 30)),
                'file_path' => 'reports/report' . $i . '.pdf',
            ]);
        }
    }
} 