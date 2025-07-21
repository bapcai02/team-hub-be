<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MessageReaction;
use App\Models\Message;
use App\Models\User;

class MessageReactionsTableSeeder extends Seeder
{
    public function run()
    {
        $messageIds = Message::pluck('id')->all();
        $userIds = User::pluck('id')->all();
        $emojis = ['👍', '❤️', '😂', '😮', '😢', '😡'];
        for ($i = 1; $i <= 20; $i++) {
            MessageReaction::create([
                'message_id' => $messageIds[array_rand($messageIds)],
                'user_id' => $userIds[array_rand($userIds)],
                'emoji' => $emojis[array_rand($emojis)],
            ]);
        }
    }
} 