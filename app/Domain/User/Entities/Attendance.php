<?php

namespace App\Domain\User\Entities;

class Attendance
{
    public function __construct(
        public int $id,
        public int $employee_id,
        public string $date,
        public ?string $check_in_time = null,
        public ?string $check_out_time = null,
        public ?string $break_start_time = null,
        public ?string $break_end_time = null,
        public ?float $total_hours = null,
        public ?float $overtime_hours = null,
        public string $status = 'present',
        public ?string $notes = null,
        public ?string $location = null,
        public ?string $ip_address = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
        public ?string $deleted_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->employee_id,
            $model->date,
            $model->check_in_time?->toDateTimeString(),
            $model->check_out_time?->toDateTimeString(),
            $model->break_start_time?->toDateTimeString(),
            $model->break_end_time?->toDateTimeString(),
            $model->total_hours,
            $model->overtime_hours,
            $model->status,
            $model->notes,
            $model->location,
            $model->ip_address,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
            $model->deleted_at?->toDateTimeString(),
        );
    }
}