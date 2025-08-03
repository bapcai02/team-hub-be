<?php

namespace App\Interfaces\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Helpers\ApiResponseHelper;
use App\Services\DeviceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DeviceController extends Controller
{
    protected $deviceService;

    public function __construct(DeviceService $deviceService)
    {
        $this->deviceService = $deviceService;
    }

    public function index(Request $request)
    {
        try {
            $filters = $request->only(['type', 'status', 'department', 'search']);
            $devices = $this->deviceService->getAllDevices($filters);
            
            return ApiResponseHelper::success('devices_retrieved', $devices);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('devices_retrieval_failed', $e->getMessage());
        }
    }

    public function getStats()
    {
        try {
            $stats = $this->deviceService->getDeviceStats();
            return ApiResponseHelper::success('device_stats_retrieved', $stats);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('device_stats_retrieval_failed', $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            $query = $request->get('q', '');
            $devices = $this->deviceService->searchDevices($query);
            
            return ApiResponseHelper::success('devices_search_completed', $devices);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('devices_search_failed', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $device = $this->deviceService->getDeviceById($id);
            
            if (!$device) {
                return ApiResponseHelper::error('device_not_found', 'Device not found', 404);
            }

            return ApiResponseHelper::success('device_retrieved', $device);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('device_retrieval_failed', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:255|unique:devices',
                'type' => 'required|string|in:laptop,desktop,tablet,phone,printer,scanner,other',
                'model' => 'required|string|max:255',
                'serial_number' => 'required|string|max:255|unique:devices',
                'status' => 'required|string|in:available,in_use,maintenance,retired',
                'assigned_to' => 'nullable|integer|exists:users,id',
                'location' => 'required|string|max:255',
                'department' => 'required|string|max:255',
                'purchase_date' => 'required|date',
                'warranty_expiry' => 'required|date|after:purchase_date',
                'specifications' => 'nullable|array',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return ApiResponseHelper::error('validation_failed', $validator->errors()->first());
            }

            $device = $this->deviceService->createDevice($request->validated());
            return ApiResponseHelper::success('device_created', $device);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('device_creation_failed', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'code' => 'sometimes|required|string|max:255|unique:devices,code,' . $id,
                'type' => 'sometimes|required|string|in:laptop,desktop,tablet,phone,printer,scanner,other',
                'model' => 'sometimes|required|string|max:255',
                'serial_number' => 'sometimes|required|string|max:255',
                'status' => 'sometimes|required|string|in:available,in_use,maintenance,retired',
                'assigned_to' => 'nullable|integer|exists:users,id',
                'location' => 'sometimes|required|string|max:255',
                'department' => 'sometimes|required|string|max:255',
                'purchase_date' => 'sometimes|required|date',
                'warranty_expiry' => 'sometimes|required|date',
                'specifications' => 'nullable|array',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return ApiResponseHelper::error('validation_failed', $validator->errors()->first());
            }

            $device = $this->deviceService->updateDevice($id, $request->validated());
            return ApiResponseHelper::success('device_updated', $device);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('device_update_failed', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->deviceService->deleteDevice($id);
            return ApiResponseHelper::success('device_deleted', ['id' => $id]);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('device_deletion_failed', $e->getMessage());
        }
    }

    public function assign(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|exists:users,id',
            ]);

            if ($validator->fails()) {
                return ApiResponseHelper::error('validation_failed', $validator->errors()->first());
            }

            $device = $this->deviceService->assignDevice($id, $request->user_id);
            return ApiResponseHelper::success('device_assigned', $device);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('device_assignment_failed', $e->getMessage());
        }
    }

    public function unassign(Request $request, $id)
    {
        try {
            $device = $this->deviceService->unassignDevice($id);
            return ApiResponseHelper::success('device_unassigned', $device);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('device_unassignment_failed', $e->getMessage());
        }
    }

    public function getHistory($id)
    {
        try {
            $history = $this->deviceService->getDeviceHistory($id);
            return ApiResponseHelper::success('device_history_retrieved', $history);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('device_history_retrieval_failed', $e->getMessage());
        }
    }
} 