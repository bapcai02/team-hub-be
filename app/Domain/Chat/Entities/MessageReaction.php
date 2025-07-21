<?php

namespace App\Domain\Chat\Entities;

class MessageReaction
{
    public function __construct(
        public int $id,
        public int $message_id,
        public int $user_id,
        public string $emoji,
        public string $created_at,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->message_id,
            $model->user_id,
            $model->reaction,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 