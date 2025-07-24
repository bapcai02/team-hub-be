<?php

namespace App\Domain\Project\Entities;

class TaskComment
{
    public function __construct(
        public int $id,
        public int $task_id,
        public int $user_id,
        public string $content,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->task_id,
            $model->user_id,
            $model->content,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 