<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\User;

class MessagesTableSeeder extends Seeder
{
    public function run()
    {
        $conversationIds = Conversation::pluck('id')->all();
        $userIds = User::pluck('id')->all();
        for ($i = 1; $i <= 30; $i++) {
            Message::create([
                'conversation_id' => $conversationIds[array_rand($conversationIds)],
                'sender_id' => $userIds[array_rand($userIds)],
                'content' => 'Nội dung tin nhắn ' . $i,
            ]);
        }
    }
} 