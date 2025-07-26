<?php

namespace App\Application\Project\Services;

use App\Domain\Project\Repositories\TaskRepositoryInterface;
use App\Domain\Project\Entities\Task;

class TaskService
{
    public function __construct(
        protected TaskRepositoryInterface $taskRepository,
    ) {}

    public function create(array $data): Task
    {
        return $this->taskRepository->create($data);
    }

    public function find($id): ?Task
    {
        return $this->taskRepository->find($id);
    }

    public function getByProjectId($projectId): array
    {
        return $this->taskRepository->getByProjectId($projectId);
    }

    public function update($id, array $data): ?Task
    {
        return $this->taskRepository->update($id, $data);
    }

    public function delete($id): bool
    {
        return $this->taskRepository->delete($id);
    }

    public function getByAssignee($userId): array
    {
        return $this->taskRepository->getByAssignee($userId);
    }

    public function getByStatus($projectId, $status): array
    {
        return $this->taskRepository->getByStatus($projectId, $status);
    }
} 