<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SalaryComponent;

class SalaryComponentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $components = [
            // Allowances
            [
                'name' => 'Basic Salary',
                'code' => 'BS',
                'type' => 'allowance',
                'calculation_type' => 'fixed',
                'amount' => 0,
                'percentage' => 0,
                'formula' => null,
                'is_taxable' => true,
                'is_active' => true,
                'description' => 'Basic salary component',
                'sort_order' => 1,
            ],
            [
                'name' => 'Housing Allowance',
                'code' => 'HA',
                'type' => 'allowance',
                'calculation_type' => 'percentage',
                'amount' => 0,
                'percentage' => 15,
                'formula' => null,
                'is_taxable' => false,
                'is_active' => true,
                'description' => 'Housing allowance based on basic salary',
                'sort_order' => 2,
            ],
            [
                'name' => 'Transport Allowance',
                'code' => 'TA',
                'type' => 'allowance',
                'calculation_type' => 'fixed',
                'amount' => 500000,
                'percentage' => 0,
                'formula' => null,
                'is_taxable' => false,
                'is_active' => true,
                'description' => 'Monthly transport allowance',
                'sort_order' => 3,
            ],
            [
                'name' => 'Meal Allowance',
                'code' => 'MA',
                'type' => 'allowance',
                'calculation_type' => 'fixed',
                'amount' => 300000,
                'percentage' => 0,
                'formula' => null,
                'is_taxable' => false,
                'is_active' => true,
                'description' => 'Daily meal allowance',
                'sort_order' => 4,
            ],
            [
                'name' => 'Performance Bonus',
                'code' => 'PB',
                'type' => 'bonus',
                'calculation_type' => 'percentage',
                'amount' => 0,
                'percentage' => 10,
                'formula' => null,
                'is_taxable' => true,
                'is_active' => true,
                'description' => 'Performance-based bonus',
                'sort_order' => 5,
            ],
            [
                'name' => 'Overtime Pay',
                'code' => 'OT',
                'type' => 'overtime',
                'calculation_type' => 'formula',
                'amount' => 0,
                'percentage' => 0,
                'formula' => 'basic_salary / 22 / 8 * 1.5 * overtime_hours',
                'is_taxable' => true,
                'is_active' => true,
                'description' => 'Overtime payment calculation',
                'sort_order' => 6,
            ],
            // Deductions
            [
                'name' => 'Social Insurance',
                'code' => 'SI',
                'type' => 'deduction',
                'calculation_type' => 'percentage',
                'amount' => 0,
                'percentage' => 8,
                'formula' => null,
                'is_taxable' => false,
                'is_active' => true,
                'description' => 'Social insurance deduction',
                'sort_order' => 7,
            ],
            [
                'name' => 'Health Insurance',
                'code' => 'HI',
                'type' => 'deduction',
                'calculation_type' => 'percentage',
                'amount' => 0,
                'percentage' => 1.5,
                'formula' => null,
                'is_taxable' => false,
                'is_active' => true,
                'description' => 'Health insurance deduction',
                'sort_order' => 8,
            ],
            [
                'name' => 'Unemployment Insurance',
                'code' => 'UI',
                'type' => 'deduction',
                'calculation_type' => 'percentage',
                'amount' => 0,
                'percentage' => 1,
                'formula' => null,
                'is_taxable' => false,
                'is_active' => true,
                'description' => 'Unemployment insurance deduction',
                'sort_order' => 9,
            ],
            [
                'name' => 'Personal Income Tax',
                'code' => 'PIT',
                'type' => 'deduction',
                'calculation_type' => 'formula',
                'amount' => 0,
                'percentage' => 0,
                'formula' => 'calculate_tax(taxable_income)',
                'is_taxable' => false,
                'is_active' => true,
                'description' => 'Personal income tax calculation',
                'sort_order' => 10,
            ],
        ];

        foreach ($components as $component) {
            SalaryComponent::create($component);
        }
    }
}
