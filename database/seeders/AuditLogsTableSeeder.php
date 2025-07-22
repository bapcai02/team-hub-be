<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AuditLog;
use App\Models\User;

class AuditLogsTableSeeder extends Seeder
{
    public function run()
    {
        $userIds = User::pluck('id')->all();
        $tables = ['users', 'projects', 'tasks', 'documents'];
        for ($i = 1; $i <= 10; $i++) {
            AuditLog::create([
                'user_id' => $userIds[array_rand($userIds)],
                'action' => 'action_' . $i,
                'target_table' => $tables[array_rand($tables)],
                'target_id' => rand(1, 20),
                'data' => json_encode(['change' => 'demo']),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder',
                'created_at' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
} 