<?php

namespace App\Domain\User\Entities;

class Contract
{
    public function __construct(
        public int $id,
        public int $employee_id,
        public string $contract_type,
        public string $start_date,
        public ?string $end_date = null,
        public float $salary,
        public string $status,
        public ?string $notes = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
        public ?string $deleted_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->employee_id,
            $model->contract_type,
            $model->start_date,
            $model->end_date,
            $model->salary,
            $model->status ?? null,
            $model->notes ?? null,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 