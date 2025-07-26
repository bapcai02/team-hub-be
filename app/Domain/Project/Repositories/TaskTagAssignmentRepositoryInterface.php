<?php

namespace App\Domain\Project\Repositories;

use App\Domain\Project\Entities\TaskTagAssignment;

interface TaskTagAssignmentRepositoryInterface
{
    public function create(array $data): TaskTagAssignment;
    public function find($id): ?TaskTagAssignment;
    public function removeFromTask($taskId, $tagId): bool;
    public function removeAllFromTask($taskId): bool;
    public function getByTaskId($taskId): array;
}