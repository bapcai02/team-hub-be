<?php

namespace App\Domain\Chat\Entities;

class ConversationParticipant
{
    public function __construct(
        public int $id,
        public int $conversation_id,
        public int $user_id,
        public string $joined_at,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->conversation_id,
            $model->user_id,
            $model->joined_at,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 