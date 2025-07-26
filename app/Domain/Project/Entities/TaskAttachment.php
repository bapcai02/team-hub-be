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
        public ?string $original_name = null,
        public ?int $file_size = null,
        public ?string $mime_type = null,
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
            $model->original_name ?? null,
            $model->file_size ?? null,
            $model->mime_type ?? null,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
}