<?php

namespace App\Domain\User\Entities;

class Payroll
{
    public function __construct(
        public int $id,
        public int $employee_id,
        public string $month,
        public float $base_salary,
        public float $allowance,
        public float $deduction,
        public float $net_salary,
        public string $status,
        public string $generated_at,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->employee_id,
            $model->salary,
            $model->bonus,
            $model->deduction,
            $model->pay_date,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 