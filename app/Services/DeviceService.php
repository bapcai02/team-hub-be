<?php

namespace App\Services;

use App\Models\Device;
use App\Models\DeviceHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DeviceService
{
    /**
     * Get all devices with optional filters.
     */
    public function getAllDevices(array $filters = [])
    {
        $query = Device::with('assignedUser');

        if (isset($filters['type'])) {
            $query->byType($filters['type']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['department'])) {
            $query->byDepartment($filters['department']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get device by ID.
     */
    public function getDeviceById(int $id)
    {
        return Device::with('assignedUser')->find($id);
    }

    /**
     * Create a new device.
     */
    public function createDevice(array $data)
    {
        return DB::transaction(function () use ($data) {
            $device = Device::create($data);

            // Log device creation
            DeviceHistory::create([
                'device_id' => $device->id,
                'action' => 'created',
                'user_id' => Auth::id(),
                'details' => 'Device added to inventory',
            ]);

            return $device->load('assignedUser');
        });
    }

    /**
     * Update device.
     */
    public function updateDevice(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $device = Device::findOrFail($id);
            $oldStatus = $device->status;
            $oldAssignedTo = $device->assigned_to;

            $device->update($data);

            // Log status change
            if (isset($data['status']) && $data['status'] !== $oldStatus) {
                DeviceHistory::create([
                    'device_id' => $device->id,
                    'action' => 'status_changed',
                    'user_id' => Auth::id(),
                    'details' => "Status changed from {$oldStatus} to {$data['status']}",
                ]);
            }

            // Log assignment change
            if (isset($data['assigned_to']) && $data['assigned_to'] !== $oldAssignedTo) {
                $action = $data['assigned_to'] ? 'assigned' : 'unassigned';
                $details = $data['assigned_to'] 
                    ? "Device assigned to user ID {$data['assigned_to']}"
                    : 'Device unassigned';

                DeviceHistory::create([
                    'device_id' => $device->id,
                    'action' => $action,
                    'user_id' => Auth::id(),
                    'details' => $details,
                ]);
            }

            return $device->load('assignedUser');
        });
    }

    /**
     * Delete device.
     */
    public function deleteDevice(int $id)
    {
        return DB::transaction(function () use ($id) {
            $device = Device::findOrFail($id);

            // Log device deletion
            DeviceHistory::create([
                'device_id' => $device->id,
                'action' => 'deleted',
                'user_id' => Auth::id(),
                'details' => 'Device removed from inventory',
            ]);

            return $device->delete();
        });
    }

    /**
     * Assign device to user.
     */
    public function assignDevice(int $deviceId, int $userId)
    {
        return DB::transaction(function () use ($deviceId, $userId) {
            $device = Device::findOrFail($deviceId);

            if (!$device->isAvailable()) {
                throw new \Exception('Device is not available for assignment');
            }

            $device->update([
                'assigned_to' => $userId,
                'status' => 'in_use',
            ]);

            // Log assignment
            DeviceHistory::create([
                'device_id' => $device->id,
                'action' => 'assigned',
                'user_id' => Auth::id(),
                'details' => "Device assigned to user ID {$userId}",
            ]);

            return $device->load('assignedUser');
        });
    }

    /**
     * Unassign device from user.
     */
    public function unassignDevice(int $deviceId)
    {
        return DB::transaction(function () use ($deviceId) {
            $device = Device::findOrFail($deviceId);

            $device->update([
                'assigned_to' => null,
                'status' => 'available',
            ]);

            // Log unassignment
            DeviceHistory::create([
                'device_id' => $device->id,
                'action' => 'unassigned',
                'user_id' => Auth::id(),
                'details' => 'Device unassigned from user',
            ]);

            return $device->load('assignedUser');
        });
    }

    /**
     * Get device statistics.
     */
    public function getDeviceStats()
    {
        try {
            $totalDevices = Device::count();
            $availableDevices = Device::where('status', 'available')->count();
            $inUseDevices = Device::where('status', 'in_use')->count();
            $maintenanceDevices = Device::where('status', 'maintenance')->count();
            $retiredDevices = Device::where('status', 'retired')->count();

            $utilizationRate = $totalDevices > 0 ? round(($inUseDevices / $totalDevices) * 100, 1) : 0;
            $maintenanceRate = $totalDevices > 0 ? round(($maintenanceDevices / $totalDevices) * 100, 1) : 0;

            // Device types statistics
            $deviceTypes = Device::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->get()
                ->map(function ($item) use ($totalDevices) {
                    return [
                        'type' => $item->type,
                        'count' => $item->count,
                        'percentage' => $totalDevices > 0 ? round(($item->count / $totalDevices) * 100, 1) : 0,
                    ];
                });

            // Department statistics
            $departments = Device::selectRaw('department, COUNT(*) as count')
                ->groupBy('department')
                ->get()
                ->map(function ($item) use ($totalDevices) {
                    return [
                        'department' => $item->department,
                        'count' => $item->count,
                        'percentage' => $totalDevices > 0 ? round(($item->count / $totalDevices) * 100, 1) : 0,
                    ];
                });

            // Recent activities (last 10)
            $recentActivities = DeviceHistory::with(['device', 'user'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'device_name' => $item->device->name,
                        'action' => $item->action,
                        'user_name' => $item->user ? $item->user->name : 'System',
                        'timestamp' => $item->created_at->toISOString(),
                    ];
                });

            // Warranty alerts (devices expiring in next 90 days)
            $warrantyAlerts = Device::where('warranty_expiry', '<=', now()->addDays(90))
                ->where('warranty_expiry', '>=', now())
                ->orderBy('warranty_expiry')
                ->limit(10)
                ->get()
                ->map(function ($item) {
                    $daysRemaining = now()->diffInDays($item->warranty_expiry, false);
                    return [
                        'id' => $item->id,
                        'device_name' => $item->name,
                        'warranty_expiry' => $item->warranty_expiry->format('Y-m-d'),
                        'days_remaining' => $daysRemaining,
                    ];
                });

            return [
                'total_devices' => $totalDevices,
                'available_devices' => $availableDevices,
                'in_use_devices' => $inUseDevices,
                'maintenance_devices' => $maintenanceDevices,
                'retired_devices' => $retiredDevices,
                'utilization_rate' => $utilizationRate,
                'maintenance_rate' => $maintenanceRate,
                'device_types' => $deviceTypes,
                'departments' => $departments,
                'recent_activities' => $recentActivities,
                'warranty_alerts' => $warrantyAlerts,
            ];
        } catch (\Exception $e) {
            throw new \Exception('Failed to get device statistics: ' . $e->getMessage());
        }
    }

    /**
     * Search devices.
     */
    public function searchDevices(string $query)
    {
        return Device::with('assignedUser')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('model', 'like', "%{$query}%")
                  ->orWhere('serial_number', 'like', "%{$query}%")
                  ->orWhere('notes', 'like', "%{$query}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get device history.
     */
    public function getDeviceHistory(int $deviceId)
    {
        return DeviceHistory::with('user')
            ->where('device_id', $deviceId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
} 