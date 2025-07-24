<?php

namespace App\Domain\Project\Entities;

class TaskTagAssignment
{
    public function __construct(
        public int $id,
        public int $task_id,
        public int $task_tag_id,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->task_id,
            $model->task_tag_id,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 