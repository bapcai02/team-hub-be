<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Expense;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expenses = [
            [
                'code' => 'EXP-2024-001',
                'title' => 'Office Supplies',
                'description' => 'Purchase of office supplies including paper, pens, and notebooks',
                'amount' => 2500000,
                'type' => 'operational',
                'status' => 'approved',
                'expense_date' => '2024-01-15',
                'due_date' => '2024-01-30',
                'employee_id' => 1,
                'department_id' => 1,
                'approved_at' => '2024-01-16 10:30:00',
            ],
            [
                'code' => 'EXP-2024-002',
                'title' => 'Business Travel',
                'description' => 'Travel expenses for client meeting in Ho Chi Minh City',
                'amount' => 8500000,
                'type' => 'travel',
                'status' => 'paid',
                'expense_date' => '2024-01-20',
                'due_date' => '2024-02-05',
                'employee_id' => 2,
                'department_id' => 2,
                'approved_at' => '2024-01-21 14:15:00',
            ],
            [
                'code' => 'EXP-2024-003',
                'title' => 'Marketing Campaign',
                'description' => 'Digital marketing campaign for new product launch',
                'amount' => 15000000,
                'type' => 'marketing',
                'status' => 'pending',
                'expense_date' => '2024-01-25',
                'due_date' => '2024-02-15',
                'employee_id' => 3,
                'department_id' => 3,
            ],
            [
                'code' => 'EXP-2024-004',
                'title' => 'Equipment Maintenance',
                'description' => 'Annual maintenance for office equipment and computers',
                'amount' => 5000000,
                'type' => 'maintenance',
                'status' => 'approved',
                'expense_date' => '2024-01-30',
                'due_date' => '2024-02-20',
                'employee_id' => 1,
                'department_id' => 1,
                'approved_at' => '2024-02-01 09:00:00',
            ],
            [
                'code' => 'EXP-2024-005',
                'title' => 'Utility Bills',
                'description' => 'Monthly electricity, water, and internet bills',
                'amount' => 3200000,
                'type' => 'utilities',
                'status' => 'paid',
                'expense_date' => '2024-02-01',
                'due_date' => '2024-02-15',
                'employee_id' => 1,
                'department_id' => 1,
                'approved_at' => '2024-02-02 11:30:00',
            ],
            [
                'code' => 'EXP-2024-006',
                'title' => 'Training Program',
                'description' => 'Employee training program for new software implementation',
                'amount' => 12000000,
                'type' => 'administrative',
                'status' => 'pending',
                'expense_date' => '2024-02-05',
                'due_date' => '2024-03-05',
                'employee_id' => 2,
                'department_id' => 2,
            ],
            [
                'code' => 'EXP-2024-007',
                'title' => 'Client Entertainment',
                'description' => 'Business lunch with potential clients',
                'amount' => 1800000,
                'type' => 'operational',
                'status' => 'approved',
                'expense_date' => '2024-02-10',
                'due_date' => '2024-02-25',
                'employee_id' => 3,
                'department_id' => 3,
                'approved_at' => '2024-02-11 16:45:00',
            ],
            [
                'code' => 'EXP-2024-008',
                'title' => 'Software License',
                'description' => 'Annual software license renewal for development tools',
                'amount' => 8000000,
                'type' => 'operational',
                'status' => 'rejected',
                'expense_date' => '2024-02-15',
                'due_date' => '2024-03-15',
                'employee_id' => 1,
                'department_id' => 1,
                'rejection_reason' => 'Budget exceeded for this quarter',
            ],
            [
                'code' => 'EXP-2024-009',
                'title' => 'Conference Attendance',
                'description' => 'Industry conference registration and travel expenses',
                'amount' => 9500000,
                'type' => 'travel',
                'status' => 'approved',
                'expense_date' => '2024-02-20',
                'due_date' => '2024-03-20',
                'employee_id' => 2,
                'department_id' => 2,
                'approved_at' => '2024-02-21 13:20:00',
            ],
            [
                'code' => 'EXP-2024-010',
                'title' => 'Office Renovation',
                'description' => 'Minor office renovation and furniture updates',
                'amount' => 25000000,
                'type' => 'maintenance',
                'status' => 'pending',
                'expense_date' => '2024-02-25',
                'due_date' => '2024-04-25',
                'employee_id' => 1,
                'department_id' => 1,
            ],
        ];

        foreach ($expenses as $expense) {
            Expense::create($expense);
        }
    }
} 