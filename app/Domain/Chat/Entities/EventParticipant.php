<?php

namespace App\Domain\Chat\Entities;

class EventParticipant
{
    public function __construct(
        public int $id,
        public int $event_id,
        public int $user_id,
        public string $status,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->event_id,
            $model->user_id,
            $model->role,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 