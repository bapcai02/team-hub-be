<?php

namespace App\Domain\Calendar\Entities;

use App\Domain\User\Entities\User;

class CalendarEventParticipant
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $eventId,
        public readonly int $userId,
        public readonly ?string $createdAt = null,
        public readonly ?string $updatedAt = null,
        public readonly ?User $user = null,
    ) {
    }

    public static function fromModel($model): self
    {
        $user = $model->user ? 
            User::fromModel($model->user) : null;
        
        return new self(
            id: $model->id,
            eventId: $model->event_id,
            userId: $model->user_id,
            createdAt: $model->created_at?->toDateTimeString(),
            updatedAt: $model->updated_at?->toDateTimeString(),
            user: $user,
        );
    }

    public function isUser(int $userId): bool
    {
        return $this->userId === $userId;
    }
} 