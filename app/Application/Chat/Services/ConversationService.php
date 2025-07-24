<?php

namespace App\Application\Chat\Services;

use App\Domain\Chat\Repositories\ConversationRepositoryInterface;
use App\Domain\Chat\Entities\Conversation;

class ConversationService
{
    public function __construct(protected ConversationRepositoryInterface $conversationRepository) {}

    public function create(array $data): Conversation
    {
        return $this->conversationRepository->create($data);
    }

    public function find($id): ?Conversation
    {
        return $this->conversationRepository->find($id);
    }

    public function findPersonalBetween($userId1, $userId2): ?Conversation
    {
        return $this->conversationRepository->findPersonalBetween($userId1, $userId2);
    }

    public function getByUserId($userId): array
    {
        return $this->conversationRepository->getByUserId($userId);
    }

    public function update($id, array $data): ?Conversation
    {
        return $this->conversationRepository->update($id, $data);
    }

    public function delete($id): bool
    {
        return $this->conversationRepository->delete($id);
    }
} 