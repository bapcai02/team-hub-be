<?php

namespace App\Interfaces\Http\Controllers;

use App\Services\ExpenseService;
use App\Helpers\ApiResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController
{
    protected $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    public function index(Request $request)
    {
        try {
            $filters = $request->only(['status', 'type', 'employee_id', 'department_id', 'date_from', 'date_to']);
            $expenses = $this->expenseService->getAllExpenses($filters);

            return ApiResponseHelper::success('expenses_retrieved', $expenses);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('expenses_retrieval_failed', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $expense = $this->expenseService->getExpenseById((int) $id);
            return ApiResponseHelper::success('expense_retrieved', $expense);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('expense_retrieval_failed', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'amount' => 'required|numeric|min:0',
                'type' => 'required|in:operational,administrative,marketing,travel,utilities,maintenance,other',
                'expense_date' => 'required|date',
                'due_date' => 'nullable|date|after:expense_date',
                'employee_id' => 'required|exists:employees,id',
                'department_id' => 'nullable|exists:departments,id',
                'receipt_file' => 'nullable|string',
                'attachments' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return ApiResponseHelper::error('validation_failed', $validator->errors());
            }

            $expense = $this->expenseService->createExpense($request->all());
            return ApiResponseHelper::success('expense_created', $expense);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('expense_creation_failed', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'amount' => 'sometimes|required|numeric|min:0',
                'type' => 'sometimes|required|in:operational,administrative,marketing,travel,utilities,maintenance,other',
                'expense_date' => 'sometimes|required|date',
                'due_date' => 'nullable|date|after:expense_date',
                'department_id' => 'nullable|exists:departments,id',
                'receipt_file' => 'nullable|string',
                'attachments' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return ApiResponseHelper::error('validation_failed', $validator->errors());
            }

            $expense = $this->expenseService->updateExpense((int) $id, $request->all());
            return ApiResponseHelper::success('expense_updated', $expense);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('expense_update_failed', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->expenseService->deleteExpense((int) $id);
            return ApiResponseHelper::success('expense_deleted');
        } catch (\Exception $e) {
            return ApiResponseHelper::error('expense_deletion_failed', $e->getMessage());
        }
    }

    public function approve($id)
    {
        try {
            $expense = $this->expenseService->approveExpense((int) $id);
            return ApiResponseHelper::success('expense_approved', $expense);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('expense_approval_failed', $e->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'rejection_reason' => 'required|string|max:500',
            ]);

            if ($validator->fails()) {
                return ApiResponseHelper::error('validation_failed', $validator->errors());
            }

            $expense = $this->expenseService->rejectExpense((int) $id, $request->rejection_reason);
            return ApiResponseHelper::success('expense_rejected', $expense);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('expense_rejection_failed', $e->getMessage());
        }
    }

    public function markAsPaid($id)
    {
        try {
            $expense = $this->expenseService->markExpenseAsPaid((int) $id);
            return ApiResponseHelper::success('expense_marked_as_paid', $expense);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('expense_payment_failed', $e->getMessage());
        }
    }

    public function getStatistics(Request $request)
    {
        try {
            $filters = $request->only(['date_from', 'date_to', 'department_id']);
            $statistics = $this->expenseService->getExpenseStatistics($filters);

            return ApiResponseHelper::success('expense_statistics_retrieved', $statistics);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('expense_statistics_retrieval_failed', $e->getMessage());
        }
    }
}
