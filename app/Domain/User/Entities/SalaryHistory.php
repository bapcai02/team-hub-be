<?php

namespace App\Domain\User\Entities;

class SalaryHistory
{
    public function __construct(
        public int $id,
        public int $employee_id,
        public string $effective_date,
        public float $old_salary,
        public float $new_salary,
        public ?string $reason = null,
        public int $created_by,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->employee_id,
            $model->old_salary,
            $model->new_salary,
            $model->changed_at,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 