<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Conversation;
use App\Models\User;

class ConversationsTableSeeder extends Seeder
{
    public function run()
    {
        $userIds = User::pluck('id')->all();
        $types = ['personal', 'group'];
        for ($i = 1; $i <= 10; $i++) {
            Conversation::create([
                'name' => 'Cuộc trò chuyện ' . $i,
                'type' => $types[array_rand($types)],
                'created_by' => $userIds[array_rand($userIds)],
            ]);
        }
    }
} 