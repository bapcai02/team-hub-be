<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContractTemplate;
use App\Models\User;

class ContractTemplatesTableSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $templates = [
            [
                'name' => 'Employment Contract Template',
                'description' => 'Standard employment contract template for full-time employees',
                'type' => 'employment',
                'content' => '<h1>EMPLOYMENT CONTRACT</h1>
<p><strong>Contract Number:</strong> {{contract_number}}</p>
<p><strong>Employee Name:</strong> {{employee_name}}</p>
<p><strong>Position:</strong> {{position}}</p>
<p><strong>Start Date:</strong> {{start_date}}</p>
<p><strong>Salary:</strong> {{salary}} {{currency}}</p>
<p><strong>Terms and Conditions:</strong></p>
<ul>
<li>Standard working hours: 8 hours per day, 40 hours per week</li>
<li>Probation period: 3 months</li>
<li>Benefits as per company policy</li>
</ul>',
                'variables' => [
                    'contract_number' => 'Auto-generated',
                    'employee_name' => 'Employee full name',
                    'position' => 'Job title',
                    'start_date' => 'Employment start date',
                    'salary' => 'Monthly salary amount',
                    'currency' => 'USD'
                ],
                'is_active' => true,
                'created_by' => $user->id,
            ],
            [
                'name' => 'Service Agreement Template',
                'description' => 'Template for service agreements with clients',
                'type' => 'service',
                'content' => '<h1>SERVICE AGREEMENT</h1>
<p><strong>Agreement Number:</strong> {{contract_number}}</p>
<p><strong>Client:</strong> {{client_name}}</p>
<p><strong>Service:</strong> {{service_description}}</p>
<p><strong>Start Date:</strong> {{start_date}}</p>
<p><strong>End Date:</strong> {{end_date}}</p>
<p><strong>Value:</strong> {{value}} {{currency}}</p>
<p><strong>Service Terms:</strong></p>
<ul>
<li>Service delivery timeline</li>
<li>Payment terms: 50% upfront, 50% upon completion</li>
<li>Quality standards and deliverables</li>
</ul>',
                'variables' => [
                    'contract_number' => 'Auto-generated',
                    'client_name' => 'Client company name',
                    'service_description' => 'Description of services',
                    'start_date' => 'Service start date',
                    'end_date' => 'Service end date',
                    'value' => 'Contract value',
                    'currency' => 'USD'
                ],
                'is_active' => true,
                'created_by' => $user->id,
            ],
            [
                'name' => 'Partnership Agreement Template',
                'description' => 'Template for business partnership agreements',
                'type' => 'partnership',
                'content' => '<h1>PARTNERSHIP AGREEMENT</h1>
<p><strong>Agreement Number:</strong> {{contract_number}}</p>
<p><strong>Partners:</strong> {{partner_names}}</p>
<p><strong>Partnership Type:</strong> {{partnership_type}}</p>
<p><strong>Start Date:</strong> {{start_date}}</p>
<p><strong>Duration:</strong> {{duration}}</p>
<p><strong>Profit Sharing:</strong> {{profit_sharing}}</p>
<p><strong>Partnership Terms:</strong></p>
<ul>
<li>Roles and responsibilities of each partner</li>
<li>Capital contribution requirements</li>
<li>Decision-making process</li>
<li>Dispute resolution procedures</li>
</ul>',
                'variables' => [
                    'contract_number' => 'Auto-generated',
                    'partner_names' => 'Names of all partners',
                    'partnership_type' => 'Type of partnership',
                    'start_date' => 'Partnership start date',
                    'duration' => 'Partnership duration',
                    'profit_sharing' => 'Profit sharing arrangement'
                ],
                'is_active' => true,
                'created_by' => $user->id,
            ],
            [
                'name' => 'Vendor Contract Template',
                'description' => 'Template for vendor/supplier contracts',
                'type' => 'vendor',
                'content' => '<h1>VENDOR CONTRACT</h1>
<p><strong>Contract Number:</strong> {{contract_number}}</p>
<p><strong>Vendor:</strong> {{vendor_name}}</p>
<p><strong>Services/Products:</strong> {{services_description}}</p>
<p><strong>Contract Period:</strong> {{start_date}} to {{end_date}}</p>
<p><strong>Contract Value:</strong> {{value}} {{currency}}</p>
<p><strong>Payment Terms:</strong> {{payment_terms}}</p>
<p><strong>Vendor Terms:</strong></p>
<ul>
<li>Quality standards and specifications</li>
<li>Delivery schedules</li>
<li>Warranty and support terms</li>
<li>Termination conditions</li>
</ul>',
                'variables' => [
                    'contract_number' => 'Auto-generated',
                    'vendor_name' => 'Vendor company name',
                    'services_description' => 'Description of services/products',
                    'start_date' => 'Contract start date',
                    'end_date' => 'Contract end date',
                    'value' => 'Contract value',
                    'currency' => 'USD',
                    'payment_terms' => 'Payment terms and schedule'
                ],
                'is_active' => true,
                'created_by' => $user->id,
            ],
        ];

        foreach ($templates as $template) {
            ContractTemplate::create($template);
        }
    }
}
