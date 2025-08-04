<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;
use App\Models\DeviceHistory;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $devices = [
            [
                'name' => 'MacBook Pro 2023',
                'code' => 'DEV001',
                'type' => 'laptop',
                'category_id' => 1, // Laptops
                'model' => 'MacBook Pro 14" M2',
                'serial_number' => 'MBP2023001',
                'status' => 'in_use',
                'assigned_to' => 1,
                'location' => 'Office A - Floor 2',
                'department' => 'IT',
                'purchase_date' => '2023-01-15',
                'warranty_expiry' => '2026-01-15',
                'specifications' => [
                    'processor' => 'Apple M2',
                    'memory' => '16GB',
                    'storage' => '512GB SSD',
                    'os' => 'macOS Ventura',
                ],
                'notes' => 'Primary development machine',
            ],
            [
                'name' => 'Dell XPS 15',
                'code' => 'DEV002',
                'type' => 'laptop',
                'category_id' => 1, // Laptops
                'model' => 'XPS 15 9520',
                'serial_number' => 'DLL2023002',
                'status' => 'available',
                'assigned_to' => null,
                'location' => 'Storage Room',
                'department' => 'IT',
                'purchase_date' => '2023-03-20',
                'warranty_expiry' => '2026-03-20',
                'specifications' => [
                    'processor' => 'Intel i7-12700H',
                    'memory' => '32GB',
                    'storage' => '1TB SSD',
                    'os' => 'Windows 11 Pro',
                ],
                'notes' => 'Backup development machine',
            ],
            [
                'name' => 'HP LaserJet Pro',
                'code' => 'DEV003',
                'type' => 'printer',
                'category_id' => 5, // Printers
                'model' => 'LaserJet Pro M404n',
                'serial_number' => 'HP2023003',
                'status' => 'in_use',
                'assigned_to' => null,
                'location' => 'Print Room',
                'department' => 'Admin',
                'purchase_date' => '2023-02-10',
                'warranty_expiry' => '2025-02-10',
                'specifications' => [
                    'processor' => 'N/A',
                    'memory' => 'N/A',
                    'storage' => 'N/A',
                    'os' => 'N/A',
                ],
                'notes' => 'Main office printer',
            ],
            [
                'name' => 'iPad Pro 12.9',
                'code' => 'DEV004',
                'type' => 'tablet',
                'category_id' => 3, // Tablets
                'model' => 'iPad Pro 12.9" 6th Gen',
                'serial_number' => 'IPD2023004',
                'status' => 'maintenance',
                'assigned_to' => 2,
                'location' => 'Design Department',
                'department' => 'Design',
                'purchase_date' => '2023-04-05',
                'warranty_expiry' => '2025-04-05',
                'specifications' => [
                    'processor' => 'Apple M2',
                    'memory' => '8GB',
                    'storage' => '256GB',
                    'os' => 'iPadOS 16',
                ],
                'notes' => 'Screen replacement needed',
            ],
            [
                'name' => 'iPhone 14 Pro',
                'code' => 'DEV005',
                'type' => 'phone',
                'category_id' => 4, // Phones
                'model' => 'iPhone 14 Pro 128GB',
                'serial_number' => 'IPH2023005',
                'status' => 'in_use',
                'assigned_to' => 3,
                'location' => 'Sales Department',
                'department' => 'Sales',
                'purchase_date' => '2023-05-12',
                'warranty_expiry' => '2025-05-12',
                'specifications' => [
                    'processor' => 'Apple A16 Bionic',
                    'memory' => '6GB',
                    'storage' => '128GB',
                    'os' => 'iOS 16',
                ],
                'notes' => 'Company phone for sales team',
            ],
        ];

        foreach ($devices as $deviceData) {
            $device = Device::create($deviceData);

            // Create history entry for device creation
            DeviceHistory::create([
                'device_id' => $device->id,
                'action' => 'created',
                'user_id' => 1,
                'details' => 'Device added to inventory',
            ]);

            // Create additional history entries for some devices
            if ($device->assigned_to) {
                DeviceHistory::create([
                    'device_id' => $device->id,
                    'action' => 'assigned',
                    'user_id' => 1,
                    'details' => "Device assigned to user ID {$device->assigned_to}",
                ]);
            }

            if ($device->status === 'maintenance') {
                DeviceHistory::create([
                    'device_id' => $device->id,
                    'action' => 'maintenance',
                    'user_id' => 1,
                    'details' => 'Device sent for maintenance',
                ]);
            }
        }
    }
} 