<?php

namespace App\Interfaces\Http\Controllers;

use App\Services\ExpenseService;
use App\Helpers\ApiResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class ExpenseController
{
    public function __construct(protected ExpenseService $expenseService) {}

    /**
     * Get all expenses with filters.
     */
    public function getAllExpenses(Request $request)
    {
        try {
            $filters = $request->all();
            $expenses = $this->expenseService->getAllExpenses($filters);
            return ApiResponseHelper::responseApi(['expenses' => $expenses], 'expenses_retrieved_successfully');
        } catch (\Exception $e) {
            Log::error('ExpenseController::getAllExpenses - Error getting expenses', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        }
    }

    /**
     * Get expense by ID.
     */
    public function getExpenseById(Request $request, $id)
    {
        try {
            $expense = $this->expenseService->getExpenseById((int) $id);
            return ApiResponseHelper::responseApi(['expense' => $expense], 'expense_retrieved_successfully');
        } catch (\Exception $e) {
            Log::error('ExpenseController::getExpenseById - Error getting expense', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        }
    }

    /**
     * Create new expense.
     */
    public function createExpense(Request $request)
    {
        try {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'amount' => 'required|numeric|min:0',
                'type' => 'required|string|in:travel,office,marketing,training,other',
                'date' => 'required|date',
                'employee_id' => 'required|integer|exists:employees,id',
                'status' => 'nullable|string|in:pending,approved,rejected,paid',
                'receipt_url' => 'nullable|string'
            ]);

            $expense = $this->expenseService->createExpense($data);
            return ApiResponseHelper::responseApi(['expense' => $expense], 'expense_created_successfully');
        } catch (\Exception $e) {
            Log::error('ExpenseController::createExpense - Error creating expense', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        }
    }

    /**
     * Update expense.
     */
    public function updateExpense(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'title' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'amount' => 'nullable|numeric|min:0',
                'type' => 'nullable|string|in:travel,office,marketing,training,other',
                'date' => 'nullable|date',
                'employee_id' => 'nullable|integer|exists:employees,id',
                'status' => 'nullable|string|in:pending,approved,rejected,paid',
                'receipt_url' => 'nullable|string'
            ]);

            $expense = $this->expenseService->updateExpense((int) $id, $data);
            return ApiResponseHelper::responseApi(['expense' => $expense], 'expense_updated_successfully');
        } catch (\Exception $e) {
            Log::error('ExpenseController::updateExpense - Error updating expense', [
                'error' => $e->getMessage(),
                'id' => $id,
                'data' => $request->all()
            ]);
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        }
    }

    /**
     * Delete expense.
     */
    public function deleteExpense(Request $request, $id)
    {
        try {
            $this->expenseService->deleteExpense((int) $id);
            return ApiResponseHelper::responseApi([], 'expense_deleted_successfully');
        } catch (\Exception $e) {
            Log::error('ExpenseController::deleteExpense - Error deleting expense', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        }
    }

    /**
     * Get expense statistics.
     */
    public function getExpenseStatistics(Request $request)
    {
        try {
            $stats = $this->expenseService->getExpenseStatistics();
            return ApiResponseHelper::responseApi(['statistics' => $stats], 'expense_statistics_retrieved_successfully');
        } catch (\Exception $e) {
            Log::error('ExpenseController::getExpenseStatistics - Error getting expense statistics', [
                'error' => $e->getMessage()
            ]);
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        }
    }

    /**
     * Mark expense as paid.
     */
    public function markExpenseAsPaid(Request $request, $id)
    {
        try {
            $expense = $this->expenseService->markExpenseAsPaid((int) $id);
            return ApiResponseHelper::responseApi(['expense' => $expense], 'expense_marked_as_paid_successfully');
        } catch (\Exception $e) {
            Log::error('ExpenseController::markExpenseAsPaid - Error marking expense as paid', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        }
    }

    /**
     * Export expenses to CSV.
     */
    public function exportToCsv(Request $request)
    {
        try {
            $filters = $request->all();
            $expenses = $this->expenseService->getAllExpenses($filters);

            // Set headers for download
            $filename = 'expenses_report_' . date('Y-m-d_H-i-s') . '.csv';
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            // Create output stream
            $output = fopen('php://output', 'w');

            // Add UTF-8 BOM for proper encoding
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

            // Add headers
            $headers = [
                'ID', 'Title', 'Description', 'Amount', 'Type', 'Date', 
                'Employee', 'Department', 'Status', 'Created At'
            ];
            fputcsv($output, $headers);

            // Add data
            foreach ($expenses['data'] as $expense) {
                $row = [
                    $expense->id,
                    $expense->title,
                    $expense->description,
                    number_format($expense->amount, 2),
                    ucfirst($expense->type),
                    $expense->date,
                    $expense->employee->user->name ?? 'N/A',
                    $expense->employee->department->name ?? 'N/A',
                    ucfirst($expense->status),
                    $expense->created_at
                ];
                fputcsv($output, $row);
            }

            // Add summary
            fputcsv($output, []); // Empty row
            fputcsv($output, ['SUMMARY']);
            fputcsv($output, ['Total Expenses:', count($expenses['data'])]);
            fputcsv($output, ['Total Amount:', '$' . number_format(array_sum(array_column($expenses['data'], 'amount')), 2)]);

            fclose($output);
            exit;

        } catch (\Exception $e) {
            Log::error('ExpenseController::exportToCsv - Error exporting expenses to CSV', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        }
    }
}
