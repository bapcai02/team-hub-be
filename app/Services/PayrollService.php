<?php

namespace App\Services;

use App\Models\Payroll;
use App\Models\Employee;
use App\Models\SalaryComponent;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PayrollService
{
    public function getAllPayrolls(array $filters = [])
    {
        $query = Payroll::with(['employee', 'approvedBy']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['employee_id'])) {
            $query->where('employee_id', $filters['employee_id']);
        }

        if (isset($filters['month'])) {
            $query->whereMonth('pay_period_start', $filters['month']);
        }

        if (isset($filters['year'])) {
            $query->whereYear('pay_period_start', $filters['year']);
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    public function getPayrollById(int $id)
    {
        if ($id <= 0) {
            throw new \Exception('Invalid payroll ID');
        }
        
        return Payroll::with(['employee', 'approvedBy', 'items.salaryComponent'])
            ->findOrFail($id);
    }

    public function createPayroll(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Generate payroll code
            $code = 'PAY-' . date('Y-m') . '-' . str_pad(Payroll::whereYear('created_at', date('Y'))->count() + 1, 3, '0', STR_PAD_LEFT);

            // Calculate payroll components
            $payrollData = $this->calculatePayroll($data);
            $payrollData['code'] = $code;

            $payroll = Payroll::create($payrollData);

            // Create payroll items
            $this->createPayrollItems($payroll, $data);

            return $payroll->load(['employee', 'items.salaryComponent']);
        });
    }

    public function updatePayroll(int $id, array $data)
    {
        if ($id <= 0) {
            throw new \Exception('Invalid payroll ID');
        }
        
        return DB::transaction(function () use ($id, $data) {
            $payroll = Payroll::findOrFail($id);

            if ($payroll->status !== 'draft') {
                throw new \Exception('Cannot update payroll that is not in draft status');
            }

            // Recalculate payroll
            $payrollData = $this->calculatePayroll(array_merge($payroll->toArray(), $data));
            $payroll->update($payrollData);

            // Update payroll items
            $payroll->items()->delete();
            $this->createPayrollItems($payroll, array_merge($payroll->toArray(), $data));

            return $payroll->load(['employee', 'items.salaryComponent']);
        });
    }

    public function deletePayroll(int $id)
    {
        if ($id <= 0) {
            throw new \Exception('Invalid payroll ID');
        }
        
        $payroll = Payroll::findOrFail($id);

        if ($payroll->status !== 'draft') {
            throw new \Exception('Cannot delete payroll that is not in draft status');
        }

        return $payroll->delete();
    }

    public function approvePayroll(int $id)
    {
        if ($id <= 0) {
            throw new \Exception('Invalid payroll ID');
        }
        
        $payroll = Payroll::findOrFail($id);

        if (!$payroll->canBeApproved()) {
            throw new \Exception('Payroll cannot be approved');
        }

        $payroll->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return $payroll->load(['employee', 'approvedBy']);
    }

    public function markPayrollAsPaid(int $id)
    {
        if ($id <= 0) {
            throw new \Exception('Invalid payroll ID');
        }
        
        $payroll = Payroll::findOrFail($id);

        if (!$payroll->canBePaid()) {
            throw new \Exception('Payroll cannot be marked as paid');
        }

        $payroll->update([
            'status' => 'paid',
            'payment_date' => now(),
        ]);

        return $payroll->load(['employee']);
    }

    public function generatePayrolls(array $data)
    {
        return DB::transaction(function () use ($data) {
            $generatedPayrolls = [];

            foreach ($data['employee_ids'] as $employeeId) {
                $employee = Employee::find($employeeId);
                
                // Check if payroll already exists for this period
                $existingPayroll = Payroll::where('employee_id', $employeeId)
                    ->where('pay_period_start', $data['pay_period_start'])
                    ->where('pay_period_end', $data['pay_period_end'])
                    ->first();

                if ($existingPayroll) {
                    continue; // Skip if payroll already exists
                }

                // Calculate working days from attendance
                $workingDays = Attendance::where('employee_id', $employeeId)
                    ->whereBetween('date', [$data['pay_period_start'], $data['pay_period_end']])
                    ->where('status', 'present')
                    ->count();

                // Calculate overtime hours
                $overtimeHours = Attendance::where('employee_id', $employeeId)
                    ->whereBetween('date', [$data['pay_period_start'], $data['pay_period_end']])
                    ->sum('overtime_hours');

                $payrollData = [
                    'employee_id' => $employeeId,
                    'pay_period_start' => $data['pay_period_start'],
                    'pay_period_end' => $data['pay_period_end'],
                    'basic_salary' => $employee->salary ?? 0,
                    'working_days' => $workingDays,
                    'overtime_hours' => $overtimeHours,
                ];

                $calculatedData = $this->calculatePayroll($payrollData);
                $calculatedData['code'] = 'PAY-' . date('Y-m') . '-' . str_pad(Payroll::whereYear('created_at', date('Y'))->count() + 1, 3, '0', STR_PAD_LEFT);

                $payroll = Payroll::create($calculatedData);
                $this->createPayrollItems($payroll, $payrollData);

                $generatedPayrolls[] = $payroll->load(['employee']);
            }

            return $generatedPayrolls;
        });
    }

    private function calculatePayroll($data)
    {
        $basicSalary = $data['basic_salary'] ?? 0;
        $workingDays = $data['working_days'] ?? 0;
        $overtimeHours = $data['overtime_hours'] ?? 0;
        $bonus = $data['bonus'] ?? 0;

        // Get salary components
        $allowances = SalaryComponent::active()->allowances()->get();
        $deductions = SalaryComponent::active()->deductions()->get();

        // Calculate allowances
        $totalAllowances = 0;
        foreach ($allowances as $allowance) {
            $amount = $allowance->calculateAmount($basicSalary, $overtimeHours, $workingDays);
            $totalAllowances += $amount;
        }

        // Calculate deductions
        $totalDeductions = 0;
        foreach ($deductions as $deduction) {
            $amount = $deduction->calculateAmount($basicSalary, $overtimeHours, $workingDays);
            $totalDeductions += $amount;
        }

        // Calculate overtime pay (assuming 1.5x rate)
        $hourlyRate = $basicSalary / (22 * 8); // Assuming 22 working days, 8 hours per day
        $overtimePay = $overtimeHours * $hourlyRate * 1.5;

        // Calculate gross salary
        $grossSalary = $basicSalary + $totalAllowances + $overtimePay + $bonus;

        // Calculate tax (simplified - 10% of gross salary)
        $taxAmount = $grossSalary * 0.1;

        // Calculate insurance (simplified - 5% of gross salary)
        $insuranceAmount = $grossSalary * 0.05;

        // Calculate net salary
        $netSalary = $grossSalary - $totalDeductions - $taxAmount - $insuranceAmount;

        return [
            'employee_id' => $data['employee_id'],
            'pay_period_start' => $data['pay_period_start'],
            'pay_period_end' => $data['pay_period_end'],
            'basic_salary' => $basicSalary,
            'gross_salary' => $grossSalary,
            'net_salary' => $netSalary,
            'total_allowances' => $totalAllowances,
            'total_deductions' => $totalDeductions,
            'overtime_pay' => $overtimePay,
            'bonus' => $bonus,
            'tax_amount' => $taxAmount,
            'insurance_amount' => $insuranceAmount,
            'working_days' => $workingDays,
            'overtime_hours' => $overtimeHours,
            'notes' => $data['notes'] ?? null,
        ];
    }

    private function createPayrollItems($payroll, $data)
    {
        $basicSalary = $data['basic_salary'] ?? 0;
        $workingDays = $data['working_days'] ?? 0;
        $overtimeHours = $data['overtime_hours'] ?? 0;
        $bonus = $data['bonus'] ?? 0;

        // Create allowance items
        $allowances = SalaryComponent::active()->allowances()->get();
        foreach ($allowances as $allowance) {
            $amount = $allowance->calculateAmount($basicSalary, $overtimeHours, $workingDays);
            if ($amount > 0) {
                $payroll->items()->create([
                    'salary_component_id' => $allowance->id,
                    'component_name' => $allowance->name,
                    'type' => 'allowance',
                    'amount' => $amount,
                    'rate' => $allowance->amount,
                    'quantity' => $allowance->calculation_type === 'percentage' ? 1 : $workingDays,
                    'description' => $allowance->description,
                    'is_taxable' => $allowance->is_taxable,
                ]);
            }
        }

        // Create deduction items
        $deductions = SalaryComponent::active()->deductions()->get();
        foreach ($deductions as $deduction) {
            $amount = $deduction->calculateAmount($basicSalary, $overtimeHours, $workingDays);
            if ($amount > 0) {
                $payroll->items()->create([
                    'salary_component_id' => $deduction->id,
                    'component_name' => $deduction->name,
                    'type' => 'deduction',
                    'amount' => $amount,
                    'rate' => $deduction->amount,
                    'quantity' => $deduction->calculation_type === 'percentage' ? 1 : $workingDays,
                    'description' => $deduction->description,
                    'is_taxable' => $deduction->is_taxable,
                ]);
            }
        }

        // Create overtime item
        if ($overtimeHours > 0) {
            $hourlyRate = $basicSalary / (22 * 8);
            $overtimePay = $overtimeHours * $hourlyRate * 1.5;
            
            $payroll->items()->create([
                'salary_component_id' => null,
                'component_name' => 'Overtime Pay',
                'type' => 'overtime',
                'amount' => $overtimePay,
                'rate' => $hourlyRate * 1.5,
                'quantity' => $overtimeHours,
                'description' => "Overtime hours: {$overtimeHours}",
                'is_taxable' => true,
            ]);
        }

        // Create bonus item
        if ($bonus > 0) {
            $payroll->items()->create([
                'salary_component_id' => null,
                'component_name' => 'Bonus',
                'type' => 'bonus',
                'amount' => $bonus,
                'rate' => $bonus,
                'quantity' => 1,
                'description' => 'Performance bonus',
                'is_taxable' => true,
            ]);
        }
    }
} 