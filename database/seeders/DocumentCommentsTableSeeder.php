<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentComment;
use App\Models\Document;
use App\Models\User;

class DocumentCommentsTableSeeder extends Seeder
{
    public function run()
    {
        $documentIds = Document::pluck('id')->all();
        $userIds = User::pluck('id')->all();
        for ($i = 1; $i <= 20; $i++) {
            DocumentComment::create([
                'document_id' => $documentIds[array_rand($documentIds)],
                'user_id' => $userIds[array_rand($userIds)],
                'comment' => 'Bình luận cho document ' . $i,
            ]);
        }
    }
} 