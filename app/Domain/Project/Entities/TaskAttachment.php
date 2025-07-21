<?php

namespace App\Domain\Project\Entities;

class TaskAttachment
{
    public function __construct(
        public int $id,
        public int $task_id,
        public string $file_path,
        public int $uploaded_by,
        public string $uploaded_at,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->task_id,
            $model->file_path,
            $model->uploaded_by,
            $model->uploaded_at,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 