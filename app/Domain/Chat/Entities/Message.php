<?php

namespace App\Domain\Chat\Entities;

class Message
{
    public function __construct(
        public int $id,
        public int $conversation_id,
        public int $sender_id,
        public string $content,
        public string $type,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->conversation_id,
            $model->sender_id,
            $model->content,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
            $model->deleted_at?->toDateTimeString(),
        );
    }
} 