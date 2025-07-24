<?php

namespace App\Domain\Project\Entities;

class KanbanColumn
{
    public function __construct(
        public int $id,
        public int $project_id,
        public string $name,
        public int $order = 0,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->project_id,
            $model->name,
            $model->order,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 