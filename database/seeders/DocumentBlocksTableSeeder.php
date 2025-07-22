<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentBlock;
use App\Models\Document;

class DocumentBlocksTableSeeder extends Seeder
{
    public function run()
    {
        $documentIds = Document::pluck('id')->all();
        $types = ['text', 'image', 'table', 'code'];
        for ($i = 1; $i <= 20; $i++) {
            DocumentBlock::create([
                'document_id' => $documentIds[array_rand($documentIds)],
                'type' => $types[array_rand($types)],
                'content' => 'Nội dung block ' . $i,
                'order' => $i,
            ]);
        }
    }
} 