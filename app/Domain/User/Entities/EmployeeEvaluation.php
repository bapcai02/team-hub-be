<?php

namespace App\Domain\User\Entities;

class EmployeeEvaluation
{
    public function __construct(
        public int $id,
        public int $employee_id,
        public int $evaluator_id,
        public string $period,
        public float $score,
        public ?string $feedback = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->employee_id,
            $model->score,
            $model->comment,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 