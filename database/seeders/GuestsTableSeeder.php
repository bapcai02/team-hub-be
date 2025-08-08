<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GuestsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('guests')->truncate();
        $guests = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'phone' => '0123456789',
                'company' => 'Acme Corp',
                'position' => 'Manager',
                'address' => '123 Main St, City',
                'type' => 'guest',
                'status' => 'active',
                'notes' => 'VIP guest',
                'avatar' => null,
                'first_visit_date' => now()->subDays(10),
                'last_visit_date' => now()->subDays(1),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'phone' => '0987654321',
                'company' => 'Beta Ltd',
                'position' => 'Partner',
                'address' => '456 Second St, City',
                'type' => 'partner',
                'status' => 'active',
                'notes' => 'Long-term partner',
                'avatar' => null,
                'first_visit_date' => now()->subDays(20),
                'last_visit_date' => now()->subDays(2),
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        foreach ($guests as $guest) {
            Guest::create($guest);
        }
    }
}