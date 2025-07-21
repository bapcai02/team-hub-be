<?php

namespace App\Domain\User\Entities;

class Leave
{
    public function __construct(
        public int $id,
        public int $employee_id,
        public string $type,
        public string $date_from,
        public string $date_to,
        public ?string $reason = null,
        public string $status,
        public ?int $approved_by = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->employee_id,
            $model->leave_type,
            $model->start_date,
            $model->end_date,
            $model->reason,
            $model->status,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 