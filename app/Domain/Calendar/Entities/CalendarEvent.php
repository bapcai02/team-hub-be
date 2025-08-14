<?php

namespace App\Domain\Calendar\Entities;

use App\Domain\User\Entities\User;

class CalendarEvent
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $userId,
        public readonly string $title,
        public readonly ?string $description,
        public readonly string $startTime,
        public readonly string $endTime,
        public readonly string $eventType,
        public readonly string $color,
        public readonly bool $isAllDay,
        public readonly ?string $location,
        public readonly string $status,
        public readonly ?string $createdAt = null,
        public readonly ?string $updatedAt = null,
        public readonly ?User $user = null,
        public readonly array $participants = [],
    ) {
    }

    public static function fromModel($model): self
    {
        $user = $model->user ? 
            User::fromModel($model->user) : null;
        
        $participants = $model->participants ? 
            array_map([User::class, 'fromModel'], $model->participants) : [];
        
        return new self(
            id: $model->id,
            userId: $model->user_id,
            title: $model->title,
            description: $model->description,
            startTime: $model->start_time->toDateTimeString(),
            endTime: $model->end_time->toDateTimeString(),
            eventType: $model->event_type,
            color: $model->color,
            isAllDay: (bool) $model->is_all_day,
            location: $model->location,
            status: $model->status,
            createdAt: $model->created_at?->toDateTimeString(),
            updatedAt: $model->updated_at?->toDateTimeString(),
            user: $user,
            participants: $participants,
        );
    }

    public function isOwnedBy(int $userId): bool
    {
        return $this->userId === $userId;
    }

    public function isUpcoming(): bool
    {
        return strtotime($this->startTime) > time();
    }

    public function isPast(): bool
    {
        return strtotime($this->endTime) < time();
    }

    public function isOngoing(): bool
    {
        $now = time();
        return strtotime($this->startTime) <= $now && strtotime($this->endTime) >= $now;
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function getDurationMinutes(): int
    {
        return (strtotime($this->endTime) - strtotime($this->startTime)) / 60;
    }

    public function hasParticipants(): bool
    {
        return !empty($this->participants);
    }

    public function getParticipantsCount(): int
    {
        return count($this->participants);
    }

    public function update(array $data): self
    {
        return new self(
            id: $this->id,
            userId: $this->userId,
            title: $data['title'] ?? $this->title,
            description: $data['description'] ?? $this->description,
            startTime: $data['start_time'] ?? $this->startTime,
            endTime: $data['end_time'] ?? $this->endTime,
            eventType: $data['event_type'] ?? $this->eventType,
            color: $data['color'] ?? $this->color,
            isAllDay: $data['is_all_day'] ?? $this->isAllDay,
            location: $data['location'] ?? $this->location,
            status: $data['status'] ?? $this->status,
            createdAt: $this->createdAt,
            updatedAt: now()->toDateTimeString(),
            user: $this->user,
            participants: $this->participants,
        );
    }
} 