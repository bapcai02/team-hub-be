<?php

namespace App\Domain\Chat\Repositories;

use App\Domain\Chat\Entities\Conversation;

interface ConversationRepositoryInterface
{
    public function create(array $data): Conversation;
    public function find($id): ?Conversation;
    public function findPersonalBetween($userId1, $userId2): ?Conversation;
    public function getByUserId($userId): array;
    public function update($id, array $data): ?Conversation;
    public function delete($id): bool;
} 