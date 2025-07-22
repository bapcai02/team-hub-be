<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MessageAttachment;
use App\Models\Message;
use App\Models\User;

class MessageAttachmentsTableSeeder extends Seeder
{
    public function run()
    {
        $messageIds = Message::pluck('id')->all();
        $userIds = User::pluck('id')->all();
        for ($i = 1; $i <= 10; $i++) {
            MessageAttachment::create([
                'message_id' => $messageIds[array_rand($messageIds)],
                'file_path' => 'attachments/file' . $i . '.txt',
                'file_type' => 'txt',
                'uploaded_by' => $userIds[array_rand($userIds)],
                'uploaded_at' => now()->subDays(rand(0, 30)),
            ]);
        }
    }
} 