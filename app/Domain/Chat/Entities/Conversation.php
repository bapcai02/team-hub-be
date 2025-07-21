<?php

namespace App\Domain\Chat\Entities;

class Conversation
{
    public function __construct(
        public int $id,
        public string $type,
        public ?string $name = null,
        public int $created_by,
        public ?int $last_message_id = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
        public ?string $deleted_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->title,
            $model->type,
            $model->created_by,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
            $model->deleted_at?->toDateTimeString(),
        );
    }
} 