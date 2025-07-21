<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaskTag;

class TaskTagsTableSeeder extends Seeder
{
    public function run()
    {
        $tags = ['bug', 'feature', 'urgent', 'review', 'research'];
        foreach ($tags as $tag) {
            TaskTag::create([
                'name' => $tag,
            ]);
        }
    }
} 