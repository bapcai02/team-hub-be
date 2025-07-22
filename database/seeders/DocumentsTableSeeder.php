<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;

class DocumentsTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            Document::create([
                'title' => 'Document ' . $i,
                'parent_id' => null,
                'created_by' => rand(1, 10),
                'visibility' => 'public',
            ]);
        }
    }
} 