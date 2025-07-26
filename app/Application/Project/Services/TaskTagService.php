<?php

namespace App\Application\Project\Services;

use App\Domain\Project\Repositories\TaskTagRepositoryInterface;
use App\Domain\Project\Repositories\TaskTagAssignmentRepositoryInterface;
use App\Domain\Project\Entities\TaskTag;

class TaskTagService
{
    public function __construct(
        protected TaskTagRepositoryInterface $taskTagRepository,
        protected TaskTagAssignmentRepositoryInterface $taskTagAssignmentRepository,
    ) {}

    public function create(array $data): TaskTag
    {
        return $this->taskTagRepository->create($data);
    }

    public function find($id): ?TaskTag
    {
        return $this->taskTagRepository->find($id);
    }

    public function getAll(): array
    {
        return $this->taskTagRepository->getAll();
    }

    public function update($id, array $data): ?TaskTag
    {
        return $this->taskTagRepository->update($id, $data);
    }

    public function delete($id): bool
    {
        return $this->taskTagRepository->delete($id);
    }

    public function getByTaskId($taskId): array
    {
        return $this->taskTagRepository->getByTaskId($taskId);
    }

    public function assignToTask($taskId, array $tagIds): void
    {
        // Remove existing assignments first
        $this->taskTagAssignmentRepository->removeAllFromTask($taskId);
        
        // Add new assignments
        foreach ($tagIds as $tagId) {
            $this->taskTagAssignmentRepository->create([
                'task_id' => $taskId,
                'task_tag_id' => $tagId,
            ]);
        }
    }

    public function removeFromTask($taskId, array $tagIds): void
    {
        foreach ($tagIds as $tagId) {
            $this->taskTagAssignmentRepository->removeFromTask($taskId, $tagId);
        }
    }
}