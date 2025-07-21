<?php

namespace App\Domain\Project\Entities;

class Project
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description = null,
        public int $owner_id,
        public string $start_date,
        public ?string $end_date = null,
        public string $status,
        public ?string $created_at = null,
        public ?string $updated_at = null,
        public ?string $deleted_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->name,
            $model->description,
            $model->owner_id,
            $model->start_date,
            $model->end_date,
            $model->status,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
            $model->deleted_at?->toDateTimeString(),
        );
    }
} 