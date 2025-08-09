<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::limit(5)->get();
        
        // Tạo notifications đơn giản dựa trên cấu trúc hiện tại
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'info',
                'data' => json_encode([
                    'title' => 'Welcome to Team Hub',
                    'message' => 'Welcome to our team management system.',
                    'category' => 'system'
                ]),
                'read_at' => null,
            ]);
            
            Notification::create([
                'user_id' => $user->id,
                'type' => 'success',
                'data' => json_encode([
                    'title' => 'Profile Updated',
                    'message' => 'Your profile has been successfully updated.',
                    'category' => 'system'
                ]),
                'read_at' => null,
            ]);
            
            Notification::create([
                'user_id' => $user->id,
                'type' => 'warning',
                'data' => json_encode([
                    'title' => 'Contract Pending',
                    'message' => 'You have a contract waiting for your signature.',
                    'category' => 'contract'
                ]),
                'read_at' => null,
            ]);
        }
    }
} 