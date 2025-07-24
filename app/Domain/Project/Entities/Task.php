<?php

namespace App\Domain\Project\Entities;

class Task
{
    public function __construct(
        public int $id,
        public int $project_id,
        public ?int $assigned_to = null,
        public string $title,
        public ?string $description = null,
        public string $status,
        public string $priority,
        public ?string $deadline = null,
        public int $created_by,
        public ?string $created_at = null,
        public ?string $updated_at = null,
        public ?string $deleted_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->project_id,
            $model->assigned_to,
            $model->title,
            $model->description,
            $model->status,
            $model->priority,
            $model->deadline,
            $model->created_by,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
            $model->deleted_at?->toDateTimeString(),
        );
    }
} 