<?php

namespace App\Interfaces\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Helpers\ApiResponseHelper;
use App\Models\DeviceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeviceCategoryController extends Controller
{
    public function index()
    {
        try {
            $categories = DeviceCategory::active()->orderBy('name')->get();
            return ApiResponseHelper::success('device_categories_retrieved', $categories);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('device_categories_retrieval_failed', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $category = DeviceCategory::find($id);
            
            if (!$category) {
                return ApiResponseHelper::error('device_category_not_found', 'Device category not found', 404);
            }

            return ApiResponseHelper::success('device_category_retrieved', $category);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('device_category_retrieval_failed', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:255|unique:device_categories',
                'slug' => 'required|string|max:255|unique:device_categories',
                'description' => 'nullable|string',
                'icon' => 'nullable|string|max:255',
                'is_active' => 'boolean',
            ]);

            if ($validator->fails()) {
                return ApiResponseHelper::error('validation_failed', $validator->errors()->first());
            }

            $category = DeviceCategory::create($request->validated());
            return ApiResponseHelper::success('device_category_created', $category);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('device_category_creation_failed', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'code' => 'sometimes|required|string|max:255|unique:device_categories,code,' . $id,
                'slug' => 'sometimes|required|string|max:255|unique:device_categories,slug,' . $id,
                'description' => 'nullable|string',
                'icon' => 'nullable|string|max:255',
                'is_active' => 'boolean',
            ]);

            if ($validator->fails()) {
                return ApiResponseHelper::error('validation_failed', $validator->errors()->first());
            }

            $category = DeviceCategory::findOrFail($id);
            $category->update($request->validated());

            return ApiResponseHelper::success('device_category_updated', $category);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('device_category_update_failed', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $category = DeviceCategory::findOrFail($id);
            $category->delete();

            return ApiResponseHelper::success('device_category_deleted', ['id' => $id]);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('device_category_deletion_failed', $e->getMessage());
        }
    }
} 