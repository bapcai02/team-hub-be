<?php

namespace App\Domain\Project\Entities;

class TaskTag
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $color = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->name,
            $model->color,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 