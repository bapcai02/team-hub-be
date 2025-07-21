<?php

namespace App\Domain\Other\Entities;

class Todo
{
    public function __construct(
        public int $id,
        public int $user_id,
        public string $title,
        public ?string $due_date = null,
        public bool $is_done = false,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->user_id,
            $model->title,
            $model->due_date,
            $model->is_done,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 