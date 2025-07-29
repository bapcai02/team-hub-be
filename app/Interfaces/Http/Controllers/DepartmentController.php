<?php

namespace App\Interfaces\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Helpers\ApiResponseHelper;
use App\Models\Department;

class DepartmentController
{
    /**
     * Get all departments.
     */
    public function index()
    {
        try {
            $departments = Department::all();
            return ApiResponseHelper::responseApi(['data' => $departments], 'departments_list_success');
        } catch (\Throwable $e) {
            Log::error('DepartmentController::index - Error getting departments', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get department by ID.
     */
    public function show($id)
    {
        try {
            $department = Department::find($id);
            if (!$department) {
                return ApiResponseHelper::responseApi([], 'department_not_found', 404);
            }
            return ApiResponseHelper::responseApi(['data' => $department], 'department_show_success');
        } catch (\Throwable $e) {
            Log::error('DepartmentController::show - Error getting department', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }
} 