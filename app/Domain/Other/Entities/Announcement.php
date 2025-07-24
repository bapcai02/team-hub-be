<?php

namespace App\Domain\Other\Entities;

class Announcement
{
    public function __construct(
        public int $id,
        public string $title,
        public string $content,
        public string $visible_to,
        public string $start_date,
        public string $end_date,
        public int $created_by,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->title,
            $model->content,
            $model->created_by,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 