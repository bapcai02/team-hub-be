<?php

namespace App\Services;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class ExpenseService
{
    public function getAllExpenses(array $filters = [])
    {
        $query = Expense::with(['employee', 'department', 'approvedBy']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        if (isset($filters['department_id'])) {
            $query->where('department_id', $filters['department_id']);
        }

        if (isset($filters['date_from'])) {
            $query->where('expense_date', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('expense_date', '<=', $filters['date_to']);
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    public function getExpenseById(int $id)
    {
        if ($id <= 0) {
            throw new \Exception('Invalid expense ID');
        }
        
        return Expense::with(['employee', 'department', 'approvedBy'])
            ->findOrFail($id);
    }

    public function createExpense(array $data)
    {
        // Generate expense code
        $code = 'EXP-' . date('Y') . '-' . str_pad(Expense::whereYear('created_at', date('Y'))->count() + 1, 3, '0', STR_PAD_LEFT);

        return Expense::create([
            'code' => $code,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'amount' => $data['amount'],
            'type' => $data['type'],
            'expense_date' => $data['expense_date'],
            'due_date' => $data['due_date'] ?? null,
            'employee_id' => $data['employee_id'],
            'department_id' => $data['department_id'] ?? null,
            'receipt_file' => $data['receipt_file'] ?? null,
            'attachments' => $data['attachments'] ?? null,
            'status' => 'pending',
        ]);
    }

    public function updateExpense(int $id, array $data)
    {
        if ($id <= 0) {
            throw new \Exception('Invalid expense ID');
        }
        
        $expense = Expense::findOrFail($id);

        if ($expense->status !== 'pending') {
            throw new \Exception('Cannot update expense that is not pending');
        }

        $expense->update($data);

        return $expense->load(['employee', 'department']);
    }

    public function deleteExpense(int $id)
    {
        if ($id <= 0) {
            throw new \Exception('Invalid expense ID');
        }
        
        $expense = Expense::findOrFail($id);

        if ($expense->status !== 'pending') {
            throw new \Exception('Cannot delete expense that is not pending');
        }

        return $expense->delete();
    }

    public function approveExpense(int $id)
    {
        if ($id <= 0) {
            throw new \Exception('Invalid expense ID');
        }
        
        $expense = Expense::findOrFail($id);

        if (!$expense->canBeApproved()) {
            throw new \Exception('Expense cannot be approved');
        }

        $expense->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return $expense->load(['employee', 'department', 'approvedBy']);
    }

    public function rejectExpense(int $id, string $rejectionReason)
    {
        if ($id <= 0) {
            throw new \Exception('Invalid expense ID');
        }
        
        $expense = Expense::findOrFail($id);

        if (!$expense->canBeRejected()) {
            throw new \Exception('Expense cannot be rejected');
        }

        $expense->update([
            'status' => 'rejected',
            'rejection_reason' => $rejectionReason,
        ]);

        return $expense->load(['employee', 'department']);
    }

    public function markExpenseAsPaid(int $id)
    {
        if ($id <= 0) {
            throw new \Exception('Invalid expense ID');
        }
        
        $expense = Expense::findOrFail($id);

        if (!$expense->canBePaid()) {
            throw new \Exception('Expense cannot be marked as paid');
        }

        $expense->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return $expense->load(['employee', 'department']);
    }

    public function getExpenseStatistics(array $filters = [])
    {
        $query = Expense::query();

        // Apply date filters
        if (isset($filters['date_from'])) {
            $query->where('expense_date', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('expense_date', '<=', $filters['date_to']);
        }

        // Apply department filter
        if (isset($filters['department_id'])) {
            $query->where('department_id', $filters['department_id']);
        }

        $totalExpenses = $query->count();
        $totalAmount = $query->sum('amount');
        $pendingAmount = $query->where('status', 'pending')->sum('amount');
        $approvedAmount = $query->where('status', 'approved')->sum('amount');
        $paidAmount = $query->where('status', 'paid')->sum('amount');

        // Expenses by type
        $expensesByType = $query->selectRaw('type, COUNT(*) as count, SUM(amount) as total_amount')
            ->groupBy('type')
            ->get();

        // Expenses by status
        $expensesByStatus = $query->selectRaw('status, COUNT(*) as count, SUM(amount) as total_amount')
            ->groupBy('status')
            ->get();

        // Monthly expenses
        $monthlyExpenses = $query->selectRaw('YEAR(expense_date) as year, MONTH(expense_date) as month, SUM(amount) as total_amount')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        return [
            'total_expenses' => $totalExpenses,
            'total_amount' => $totalAmount,
            'pending_amount' => $pendingAmount,
            'approved_amount' => $approvedAmount,
            'paid_amount' => $paidAmount,
            'expenses_by_type' => $expensesByType,
            'expenses_by_status' => $expensesByStatus,
            'monthly_expenses' => $monthlyExpenses,
        ];
    }
} 