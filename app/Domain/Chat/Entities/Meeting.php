<?php

namespace App\Domain\Chat\Entities;

class Meeting
{
    public function __construct(
        public int $id,
        public int $conversation_id,
        public string $title,
        public ?string $description = null,
        public string $start_time,
        public int $duration_minutes,
        public string $link,
        public string $status,
        public int $created_by,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->title,
            $model->description,
            $model->start_time,
            $model->end_time,
            $model->created_by,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
            $model->deleted_at?->toDateTimeString(),
        );
    }
} 