<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContractSignature;
use App\Models\BusinessContract;
use App\Models\User;

class ContractSignaturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contracts = BusinessContract::all();
        $users = User::all();

        foreach ($contracts as $contract) {
            // Add signatures for each contract
            $signatureTypes = ['digital', 'electronic', 'manual'];
            
            // Create 1-3 signatures per contract
            $numSignatures = rand(1, 3);
            
            for ($i = 0; $i < $numSignatures; $i++) {
                $user = $users->random();
                $signatureType = $signatureTypes[array_rand($signatureTypes)];
                
                ContractSignature::create([
                    'contract_id' => $contract->id,
                    'signer_id' => $user->id,
                    'signer_name' => $user->name,
                    'signer_email' => $user->email,
                    'signer_title' => 'Authorized Representative',
                    'signature_type' => $signatureType,
                    'signature_data' => json_encode([
                        'signature_path' => '/signatures/signature_' . $contract->id . '_' . $i . '.png',
                        'signature_hash' => md5('signature_' . $contract->id . '_' . $i),
                        'certificate_info' => 'Digital Certificate - Valid',
                    ]),
                    'ip_address' => '192.168.1.' . rand(1, 255),
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'signed_at' => now()->subDays(rand(1, 30)),
                    'metadata' => json_encode([
                        'device_type' => 'Desktop',
                        'location' => 'Office',
                        'verification_method' => 'Email verification',
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        echo "Created contract signatures for " . $contracts->count() . " contracts\n";
    }
} 