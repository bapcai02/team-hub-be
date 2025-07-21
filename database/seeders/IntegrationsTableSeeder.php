<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Integration;
use App\Models\User;

class IntegrationsTableSeeder extends Seeder
{
    public function run()
    {
        $userIds = User::pluck('id')->all();
        $types = ['slack', 'jira', 'github', 'zoom'];
        $statuses = ['active', 'inactive'];
        for ($i = 1; $i <= 5; $i++) {
            Integration::create([
                'name' => 'Integration ' . $i,
                'type' => $types[array_rand($types)],
                'config' => json_encode(['key' => 'value' . $i]),
                'status' => $statuses[array_rand($statuses)],
                'created_by' => $userIds[array_rand($userIds)],
            ]);
        }
    }
} 