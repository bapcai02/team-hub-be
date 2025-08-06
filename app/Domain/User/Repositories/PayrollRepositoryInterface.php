<?php

namespace App\Domain\User\Repositories;

interface PayrollRepositoryInterface
{
    /**
     * Get total amount of payroll.
     */
    public function getTotalAmount(): float;

    /**
     * Get monthly amount of payroll.
     */
    public function getMonthlyAmount(): float;
} 