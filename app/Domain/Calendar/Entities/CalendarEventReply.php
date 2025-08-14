<?php

namespace App\Domain\Calendar\Entities;

use App\Domain\User\Entities\User;

class CalendarEventReply
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $eventId,
        public readonly int $userId,
        public readonly string $content,
        public readonly ?int $parentReplyId,
        public readonly ?string $createdAt = null,
        public readonly ?string $updatedAt = null,
        public readonly ?string $deletedAt = null,
        public readonly ?User $user = null,
        public readonly ?CalendarEventReply $parentReply = null,
        public readonly array $replies = [],
    ) {
    }

    public static function fromModel($model): self
    {
        $user = $model->user ? 
            User::fromModel($model->user) : null;
        
        $parentReply = $model->parentReply ? 
            self::fromModel($model->parentReply) : null;
        
        $replies = $model->replies ? 
            array_map([self::class, 'fromModel'], $model->replies) : [];
        
        return new self(
            id: $model->id,
            eventId: $model->event_id,
            userId: $model->user_id,
            content: $model->content,
            parentReplyId: $model->parent_reply_id,
            createdAt: $model->created_at?->toDateTimeString(),
            updatedAt: $model->updated_at?->toDateTimeString(),
            deletedAt: $model->deleted_at?->toDateTimeString(),
            user: $user,
            parentReply: $parentReply,
            replies: $replies,
        );
    }

    public function isOwnedBy(int $userId): bool
    {
        return $this->userId === $userId;
    }

    public function isRootReply(): bool
    {
        return $this->parentReplyId === null;
    }

    public function hasReplies(): bool
    {
        return !empty($this->replies);
    }

    public function getRepliesCount(): int
    {
        return count($this->replies);
    }

    public function update(array $data): self
    {
        return new self(
            id: $this->id,
            eventId: $this->eventId,
            userId: $this->userId,
            content: $data['content'] ?? $this->content,
            parentReplyId: $this->parentReplyId,
            createdAt: $this->createdAt,
            updatedAt: now()->toDateTimeString(),
            deletedAt: $this->deletedAt,
            user: $this->user,
            parentReply: $this->parentReply,
            replies: $this->replies,
        );
    }

    public function delete(): self
    {
        return new self(
            id: $this->id,
            eventId: $this->eventId,
            userId: $this->userId,
            content: $this->content,
            parentReplyId: $this->parentReplyId,
            createdAt: $this->createdAt,
            updatedAt: $this->updatedAt,
            deletedAt: now()->toDateTimeString(),
            user: $this->user,
            parentReply: $this->parentReply,
            replies: $this->replies,
        );
    }
} 