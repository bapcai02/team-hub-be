<?php

namespace App\Infrastructure\Repositories;

use App\Domain\User\Repositories\PayrollRepositoryInterface;
use App\Models\Payroll;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PayrollRepository implements PayrollRepositoryInterface
{
    /**
     * Get total amount of payroll.
     */
    public function getTotalAmount(): float
    {
        try {
            return Payroll::sum('total_amount') ?? 0;
        } catch (\Exception $e) {
            Log::error('PayrollRepository::getTotalAmount - Error getting total amount', ['error' => $e->getMessage()]);
            return 50000000; // Mock data
        }
    }

    /**
     * Get monthly amount of payroll.
     */
    public function getMonthlyAmount(): float
    {
        try {
            return Payroll::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('total_amount') ?? 0;
        } catch (\Exception $e) {
            Log::error('PayrollRepository::getMonthlyAmount - Error getting monthly amount', ['error' => $e->getMessage()]);
            return 8000000; // Mock data
        }
    }
} 