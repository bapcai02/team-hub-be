<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationsTableSeeder extends Seeder
{
    public function run()
    {
        $userIds = User::pluck('id')->all();
        for ($i = 1; $i <= 10; $i++) {
            Notification::create([
                'user_id' => $userIds[array_rand($userIds)],
                'type' => 'info',
                'data' => json_encode(['message' => 'Thông báo ' . $i]),
                'read_at' => null,
            ]);
        }
    }
} 