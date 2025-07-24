<?php

namespace App\Domain\User\Entities;

class Reward
{
    public function __construct(
        public int $id,
        public int $employee_id,
        public string $title,
        public ?string $reason = null,
        public float $amount,
        public string $date_awarded,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->employee_id,
            $model->type,
            $model->amount,
            $model->reason,
            $model->date,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 