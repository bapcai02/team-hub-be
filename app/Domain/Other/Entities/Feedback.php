<?php

namespace App\Domain\Other\Entities;

class Feedback
{
    public function __construct(
        public int $id,
        public int $user_id,
        public string $type,
        public string $content,
        public string $status = 'new',
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->user_id,
            $model->content,
            $model->rating,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 