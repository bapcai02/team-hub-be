<?php

namespace App\Interfaces\Http\Controllers;

use App\Services\PayrollService;
use App\Helpers\ApiResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PayrollController
{
    protected $payrollService;

    public function __construct(PayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    public function index(Request $request)
    {
        try {
            $filters = $request->only(['status', 'employee_id', 'month', 'year']);
            $payrolls = $this->payrollService->getAllPayrolls($filters);

            return ApiResponseHelper::success('payrolls_retrieved', $payrolls);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('payrolls_retrieval_failed', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $payroll = $this->payrollService->getPayrollById((int) $id);
            return ApiResponseHelper::success('payroll_retrieved', $payroll);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('payroll_retrieval_failed', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'employee_id' => 'required|exists:employees,id',
                'pay_period_start' => 'required|date',
                'pay_period_end' => 'required|date|after:pay_period_start',
                'basic_salary' => 'required|numeric|min:0',
                'working_days' => 'required|integer|min:1',
                'overtime_hours' => 'nullable|integer|min:0',
                'bonus' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return ApiResponseHelper::error('validation_failed', $validator->errors());
            }

            $payroll = $this->payrollService->createPayroll($request->all());
            return ApiResponseHelper::success('payroll_created', $payroll);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('payroll_creation_failed', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'basic_salary' => 'nullable|numeric|min:0',
                'working_days' => 'nullable|integer|min:1',
                'overtime_hours' => 'nullable|integer|min:0',
                'bonus' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return ApiResponseHelper::error('validation_failed', $validator->errors());
            }

            $payroll = $this->payrollService->updatePayroll((int) $id, $request->all());
            return ApiResponseHelper::success('payroll_updated', $payroll);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('payroll_update_failed', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->payrollService->deletePayroll((int) $id);
            return ApiResponseHelper::success('payroll_deleted');
        } catch (\Exception $e) {
            return ApiResponseHelper::error('payroll_deletion_failed', $e->getMessage());
        }
    }

    public function approve($id)
    {
        try {
            $payroll = $this->payrollService->approvePayroll((int) $id);
            return ApiResponseHelper::success('payroll_approved', $payroll);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('payroll_approval_failed', $e->getMessage());
        }
    }

    public function markAsPaid($id)
    {
        try {
            $payroll = $this->payrollService->markPayrollAsPaid((int) $id);
            return ApiResponseHelper::success('payroll_marked_as_paid', $payroll);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('payroll_payment_failed', $e->getMessage());
        }
    }

    public function generatePayroll(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'employee_ids' => 'required|array',
                'employee_ids.*' => 'exists:employees,id',
                'pay_period_start' => 'required|date',
                'pay_period_end' => 'required|date|after:pay_period_start',
            ]);

            if ($validator->fails()) {
                return ApiResponseHelper::error('validation_failed', $validator->errors());
            }

            $payrolls = $this->payrollService->generatePayrolls($request->all());
            return ApiResponseHelper::success('payrolls_generated', $payrolls);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('payroll_generation_failed', $e->getMessage());
        }
    }
}
