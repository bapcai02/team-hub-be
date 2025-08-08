<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BusinessContract;
use App\Models\ContractTemplate;
use App\Models\User;
use App\Models\Employee;

class BusinessContractsTableSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $employee = Employee::first();
        $template = ContractTemplate::where('type', 'employment')->first();

        $contracts = [
            [
                'contract_number' => 'CON-202501-0001',
                'title' => 'Employment Contract - John Doe',
                'description' => 'Full-time employment contract for Software Developer position',
                'type' => 'employment',
                'template_id' => $template->id,
                'employee_id' => $employee->id,
                'value' => 5000.00,
                'currency' => 'USD',
                'start_date' => '2025-01-15',
                'end_date' => '2026-01-15',
                'status' => 'active',
                'signature_status' => 'fully_signed',
                'terms' => [
                    'working_hours' => '8 hours per day, 40 hours per week',
                    'probation_period' => '3 months',
                    'benefits' => 'Health insurance, paid time off, retirement plan'
                ],
                'created_by' => $user->id,
            ],
            [
                'contract_number' => 'CON-202501-0002',
                'title' => 'Service Agreement - ABC Company',
                'description' => 'Web development services for ABC Company',
                'type' => 'service',
                'value' => 25000.00,
                'currency' => 'USD',
                'start_date' => '2025-01-20',
                'end_date' => '2025-06-20',
                'status' => 'active',
                'signature_status' => 'partially_signed',
                'terms' => [
                    'service_description' => 'Custom web application development',
                    'payment_terms' => '50% upfront, 50% upon completion',
                    'deliverables' => 'Source code, documentation, deployment'
                ],
                'created_by' => $user->id,
            ],
            [
                'contract_number' => 'CON-202501-0003',
                'title' => 'Partnership Agreement - Tech Solutions',
                'description' => 'Strategic partnership for joint product development',
                'type' => 'partnership',
                'value' => 100000.00,
                'currency' => 'USD',
                'start_date' => '2025-02-01',
                'end_date' => '2027-02-01',
                'status' => 'pending',
                'signature_status' => 'unsigned',
                'terms' => [
                    'partnership_type' => 'Joint venture',
                    'profit_sharing' => '50-50 split',
                    'roles' => 'Development and marketing collaboration'
                ],
                'created_by' => $user->id,
            ],
            [
                'contract_number' => 'CON-202501-0004',
                'title' => 'Vendor Contract - Office Supplies',
                'description' => 'Annual office supplies and equipment contract',
                'type' => 'vendor',
                'value' => 15000.00,
                'currency' => 'USD',
                'start_date' => '2025-01-01',
                'end_date' => '2025-12-31',
                'status' => 'active',
                'signature_status' => 'fully_signed',
                'terms' => [
                    'delivery_schedule' => 'Monthly deliveries',
                    'payment_terms' => 'Net 30 days',
                    'quality_standards' => 'Premium office supplies only'
                ],
                'created_by' => $user->id,
            ],
            [
                'contract_number' => 'CON-202501-0005',
                'title' => 'Client Contract - XYZ Corporation',
                'description' => 'Consulting services for digital transformation',
                'type' => 'client',
                'value' => 75000.00,
                'currency' => 'USD',
                'start_date' => '2025-03-01',
                'end_date' => '2025-08-31',
                'status' => 'draft',
                'signature_status' => 'unsigned',
                'terms' => [
                    'service_scope' => 'Digital transformation consulting',
                    'timeline' => '6 months project duration',
                    'milestones' => 'Monthly progress reviews'
                ],
                'created_by' => $user->id,
            ],
        ];

        foreach ($contracts as $contract) {
            BusinessContract::create($contract);
        }
    }
}
