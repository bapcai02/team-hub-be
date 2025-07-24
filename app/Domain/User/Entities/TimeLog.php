<?php

namespace App\Domain\User\Entities;

class TimeLog
{
    public function __construct(
        public int $id,
        public int $employee_id,
        public string $check_in,
        public ?string $check_out = null,
        public string $date,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->employee_id,
            $model->start_time,
            $model->end_time,
            $model->duration,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 