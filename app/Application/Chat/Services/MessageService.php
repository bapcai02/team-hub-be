<?php

namespace App\Application\Chat\Services;

use App\Domain\Chat\Repositories\MessageRepositoryInterface;
use App\Domain\Chat\Entities\Message;

class MessageService
{
    public function __construct(protected MessageRepositoryInterface $messageRepository) {}

    public function create(array $data): Message
    {
        return $this->messageRepository->create($data);
    }

    public function find($id): ?Message
    {
        return $this->messageRepository->find($id);
    }

    public function getByConversationId($conversationId, $before = null, $limit = 50): array
    {
        return $this->messageRepository->getByConversationId($conversationId, $before, $limit);
    }

    public function update($id, array $data): ?Message
    {
        return $this->messageRepository->update($id, $data);
    }

    public function delete($id): bool
    {
        return $this->messageRepository->delete($id);
    }
} 