<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TrainingParticipant;
use App\Models\Training;
use App\Models\Employee;

class TrainingParticipantsTableSeeder extends Seeder
{
    public function run()
    {
        $trainingIds = Training::pluck('id')->all();
        $employeeIds = Employee::pluck('id')->all();
        if (empty($trainingIds) || empty($employeeIds)) return;
        for ($i = 1; $i <= 10; $i++) {
            TrainingParticipant::create([
                'training_id' => $trainingIds[array_rand($trainingIds)],
                'employee_id' => $employeeIds[array_rand($employeeIds)],
                'status' => 'joined',
            ]);
        }
    }
} 