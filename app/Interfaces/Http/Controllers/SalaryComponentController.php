<?php

namespace App\Interfaces\Http\Controllers;

use App\Services\SalaryComponentService;
use App\Helpers\ApiResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SalaryComponentController
{
    protected $salaryComponentService;

    public function __construct(SalaryComponentService $salaryComponentService)
    {
        $this->salaryComponentService = $salaryComponentService;
    }

    public function index(Request $request)
    {
        try {
            $filters = $request->only(['type', 'is_active']);
            $components = $this->salaryComponentService->getAllSalaryComponents($filters);

            return ApiResponseHelper::success('salary_components_retrieved', $components);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('salary_components_retrieval_failed', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $component = $this->salaryComponentService->getSalaryComponentById((int) $id);
            return ApiResponseHelper::success('salary_component_retrieved', $component);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('salary_component_retrieval_failed', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:255|unique:salary_components',
                'type' => 'required|in:allowance,deduction,bonus,overtime',
                'calculation_type' => 'required|in:fixed,percentage,formula',
                'amount' => 'nullable|numeric|min:0',
                'percentage' => 'nullable|numeric|min:0|max:100',
                'formula' => 'nullable|string',
                'is_taxable' => 'boolean',
                'is_active' => 'boolean',
                'description' => 'nullable|string',
                'sort_order' => 'nullable|integer|min:0',
            ]);

            if ($validator->fails()) {
                return ApiResponseHelper::error('validation_failed', $validator->errors());
            }

            $component = $this->salaryComponentService->createSalaryComponent($request->all());
            return ApiResponseHelper::success('salary_component_created', $component);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('salary_component_creation_failed', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'code' => 'sometimes|required|string|max:255|unique:salary_components,code,' . $id,
                'type' => 'sometimes|required|in:allowance,deduction,bonus,overtime',
                'calculation_type' => 'sometimes|required|in:fixed,percentage,formula',
                'amount' => 'nullable|numeric|min:0',
                'percentage' => 'nullable|numeric|min:0|max:100',
                'formula' => 'nullable|string',
                'is_taxable' => 'boolean',
                'is_active' => 'boolean',
                'description' => 'nullable|string',
                'sort_order' => 'nullable|integer|min:0',
            ]);

            if ($validator->fails()) {
                return ApiResponseHelper::error('validation_failed', $validator->errors());
            }

            $component = $this->salaryComponentService->updateSalaryComponent((int) $id, $request->all());
            return ApiResponseHelper::success('salary_component_updated', $component);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('salary_component_update_failed', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->salaryComponentService->deleteSalaryComponent((int) $id);
            return ApiResponseHelper::success('salary_component_deleted');
        } catch (\Exception $e) {
            return ApiResponseHelper::error('salary_component_deletion_failed', $e->getMessage());
        }
    }

    public function getByType($type)
    {
        try {
            $components = $this->salaryComponentService->getByType($type);
            return ApiResponseHelper::success('salary_components_by_type_retrieved', $components);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('salary_components_by_type_retrieval_failed', $e->getMessage());
        }
    }

    public function toggleActive($id)
    {
        try {
            $component = $this->salaryComponentService->toggleActive((int) $id);
            return ApiResponseHelper::success('salary_component_toggle_active', $component);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('salary_component_toggle_active_failed', $e->getMessage());
        }
    }
}
