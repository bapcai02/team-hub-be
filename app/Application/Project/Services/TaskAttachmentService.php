<?php

namespace App\Application\Project\Services;

use App\Domain\Project\Repositories\TaskAttachmentRepositoryInterface;
use App\Domain\Project\Entities\TaskAttachment;

class TaskAttachmentService
{
    public function __construct(
        protected TaskAttachmentRepositoryInterface $taskAttachmentRepository,
    ) {}

    public function create(array $data): TaskAttachment
    {
        return $this->taskAttachmentRepository->create($data);
    }

    public function find($id): ?TaskAttachment
    {
        return $this->taskAttachmentRepository->find($id);
    }

    public function getByTaskId($taskId): array
    {
        return $this->taskAttachmentRepository->getByTaskId($taskId);
    }

    public function delete($id): bool
    {
        return $this->taskAttachmentRepository->delete($id);
    }

    public function getByUserId($userId): array
    {
        return $this->taskAttachmentRepository->getByUserId($userId);
    }
}