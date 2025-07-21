<?php

namespace App\Domain\Chat\Entities;

class Event
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $description = null,
        public int $owner_id,
        public string $start_time,
        public string $end_time,
        public string $type,
        public string $visibility,
        public ?string $created_at = null,
        public ?string $updated_at = null,
        public ?string $deleted_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->title,
            $model->description,
            $model->start_time,
            $model->end_time,
            $model->type,
            $model->visibility,
            $model->created_by,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
            $model->deleted_at?->toDateTimeString(),
        );
    }
} 