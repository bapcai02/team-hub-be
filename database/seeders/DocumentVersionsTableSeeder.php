<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentVersion;
use App\Models\Document;
use App\Models\User;

class DocumentVersionsTableSeeder extends Seeder
{
    public function run()
    {
        $documentIds = Document::pluck('id')->all();
        $userIds = User::pluck('id')->all();
        for ($i = 1; $i <= 10; $i++) {
            DocumentVersion::create([
                'document_id' => $documentIds[array_rand($documentIds)],
                'content_snapshot' => 'Nội dung version ' . $i,
                'hash' => md5('Nội dung version ' . $i),
                'created_by' => $userIds[array_rand($userIds)],
            ]);
        }
    }
} 