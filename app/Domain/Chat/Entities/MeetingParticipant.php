<?php

namespace App\Domain\Chat\Entities;

class MeetingParticipant
{
    public function __construct(
        public int $id,
        public int $meeting_id,
        public int $user_id,
        public ?string $joined_at = null,
        public ?string $left_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->meeting_id,
            $model->user_id,
            $model->role,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 