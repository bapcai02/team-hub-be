<?php

namespace App\Domain\Chat\Repositories;

use App\Domain\Chat\Entities\Message;

interface MessageRepositoryInterface
{
    public function create(array $data): Message;
    public function find($id): ?Message;
    public function getByConversationId($conversationId, $before = null, $limit = 50): array;
    public function update($id, array $data): ?Message;
    public function delete($id): bool;
} 