<?php

namespace App\Domain\User\Entities;

class Resignation
{
    public function __construct(
        public int $id,
        public int $employee_id,
        public ?string $reason = null,
        public string $resignation_date,
        public string $last_working_day,
        public string $status,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->employee_id,
            $model->reason,
            $model->resignation_date,
            $model->status,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 