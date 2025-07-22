<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ConversationParticipant;
use App\Models\Conversation;
use App\Models\User;

class ConversationParticipantsTableSeeder extends Seeder
{
    public function run()
    {
        $conversationIds = Conversation::pluck('id')->all();
        $userIds = User::pluck('id')->all();
        for ($i = 1; $i <= 20; $i++) {
            ConversationParticipant::create([
                'conversation_id' => $conversationIds[array_rand($conversationIds)],
                'user_id' => $userIds[array_rand($userIds)],
                'joined_at' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
} 