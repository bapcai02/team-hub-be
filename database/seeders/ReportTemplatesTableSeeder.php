<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReportTemplate;
use App\Models\User;

class ReportTemplatesTableSeeder extends Seeder
{
    public function run()
    {
        $userIds = User::pluck('id')->all();
        $types = ['project', 'task', 'time', 'employee'];
        for ($i = 1; $i <= 5; $i++) {
            ReportTemplate::create([
                'name' => 'Template ' . $i,
                'type' => $types[array_rand($types)],
                'structure' => json_encode(['section' => 'Nội dung template ' . $i]),
                'created_by' => $userIds[array_rand($userIds)],
            ]);
        }
    }
} 