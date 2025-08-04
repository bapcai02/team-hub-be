<?php

namespace App\Domain\Project\Repositories;

use App\Domain\Project\Entities\TaskComment;

interface TaskCommentRepositoryInterface
{
    public function create(array $data): TaskComment;
    public function find($id): ?TaskComment;
    public function getByTaskId($taskId): array;
    public function update($id, array $data): ?TaskComment;
    public function delete($id): bool;
    public function getByUserId($userId): array;
}