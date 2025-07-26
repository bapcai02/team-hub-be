<?php

namespace App\Application\Project\Services;

use App\Domain\Project\Repositories\TaskCommentRepositoryInterface;
use App\Domain\Project\Entities\TaskComment;

class TaskCommentService
{
    public function __construct(
        protected TaskCommentRepositoryInterface $taskCommentRepository,
    ) {}

    public function create(array $data): TaskComment
    {
        return $this->taskCommentRepository->create($data);
    }

    public function find($id): ?TaskComment
    {
        return $this->taskCommentRepository->find($id);
    }

    public function getByTaskId($taskId): array
    {
        return $this->taskCommentRepository->getByTaskId($taskId);
    }

    public function update($id, array $data): ?TaskComment
    {
        return $this->taskCommentRepository->update($id, $data);
    }

    public function delete($id): bool
    {
        return $this->taskCommentRepository->delete($id);
    }

    public function getByUserId($userId): array
    {
        return $this->taskCommentRepository->getByUserId($userId);
    }
}