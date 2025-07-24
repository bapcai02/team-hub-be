<?php

namespace App\Domain\Project\Entities;

class TaskChecklist
{
    public function __construct(
        public int $id,
        public int $task_id,
        public string $title,
        public bool $is_completed = false,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->task_id,
            $model->title,
            $model->is_completed,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 