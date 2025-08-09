<?php

namespace App\Domain\User\Repositories;

interface ExpenseRepositoryInterface
{
    /**
     * Get total amount of expenses.
     */
    public function getTotalAmount(): float;

    /**
     * Get monthly amount of expenses.
     */
    public function getMonthlyAmount(): float;

    /**
     * Get expenses by category.
     */
    public function getExpensesByCategory(): array;

    /**
     * Get expense chart data.
     */
    public function getExpenseChartData(string $period): array;
} 