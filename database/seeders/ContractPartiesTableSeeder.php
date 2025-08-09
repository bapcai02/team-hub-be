<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContractParty;
use App\Models\BusinessContract;

class ContractPartiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contracts = BusinessContract::all();

        foreach ($contracts as $contract) {
            // Add parties for each contract
            ContractParty::create([
                'contract_id' => $contract->id,
                'party_type' => 'client',
                'name' => 'Client Company ' . $contract->id,
                'email' => 'client' . $contract->id . '@example.com',
                'phone' => '+1234567890',
                'address' => '123 Business St, City, State 12345',
                'company_name' => 'Client Company ' . $contract->id,
                'tax_number' => 'TAX' . str_pad($contract->id, 6, '0', STR_PAD_LEFT),
                'representative_name' => 'John Client',
                'representative_title' => 'CEO',
                'is_primary' => true,
                'additional_info' => json_encode(['industry' => 'Technology', 'size' => 'Medium']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Add vendor/supplier party for some contracts
            if (in_array($contract->type, ['vendor', 'service'])) {
                ContractParty::create([
                    'contract_id' => $contract->id,
                    'party_type' => 'vendor',
                    'name' => 'Vendor Solutions ' . $contract->id,
                    'email' => 'vendor' . $contract->id . '@example.com',
                    'phone' => '+1987654321',
                    'address' => '456 Vendor Ave, City, State 54321',
                    'company_name' => 'Vendor Solutions ' . $contract->id,
                    'tax_number' => 'VENDOR' . str_pad($contract->id, 6, '0', STR_PAD_LEFT),
                    'representative_name' => 'Jane Vendor',
                    'representative_title' => 'Sales Manager',
                    'is_primary' => false,
                    'additional_info' => json_encode(['services' => 'IT Services', 'rating' => '4.5']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Add employee party for employment contracts
            if ($contract->type === 'employment') {
                ContractParty::create([
                    'contract_id' => $contract->id,
                    'party_type' => 'employee',
                    'name' => 'Employee ' . $contract->id,
                    'email' => 'employee' . $contract->id . '@company.com',
                    'phone' => '+1555123456',
                    'address' => '789 Employee Blvd, City, State 67890',
                    'company_name' => 'Our Company',
                    'tax_number' => 'EMP' . str_pad($contract->id, 6, '0', STR_PAD_LEFT),
                    'representative_name' => 'Employee ' . $contract->id,
                    'representative_title' => 'Software Developer',
                    'is_primary' => false,
                    'additional_info' => json_encode(['department' => 'Engineering', 'level' => 'Senior']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        echo "Created contract parties for " . $contracts->count() . " contracts\n";
    }
} 