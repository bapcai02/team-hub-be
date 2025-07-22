<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Todo;

class TodosTableSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            Todo::create([
                'user_id' => rand(1, 10),
                'title' => 'Công việc ' . $i,
                'due_date' => now()->addDays(rand(1, 30)),
                'is_done' => rand(0, 1),
            ]);
        }
    }
} 