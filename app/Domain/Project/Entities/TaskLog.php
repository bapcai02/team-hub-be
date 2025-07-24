<?php

namespace App\Domain\Project\Entities;

class TaskLog
{
    public function __construct(
        public int $id,
        public int $task_id,
        public int $user_id,
        public string $start_time,
        public ?string $end_time = null,
        public int $duration = 0,
        public ?string $note = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->task_id,
            $model->user_id,
            $model->start_time,
            $model->end_time,
            $model->duration,
            $model->note,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 