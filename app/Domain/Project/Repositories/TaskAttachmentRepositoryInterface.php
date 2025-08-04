<?php

namespace App\Domain\Project\Repositories;

use App\Domain\Project\Entities\TaskAttachment;

interface TaskAttachmentRepositoryInterface
{
    public function create(array $data): TaskAttachment;
    public function find($id): ?TaskAttachment;
    public function getByTaskId($taskId): array;
    public function delete($id): bool;
    public function getByUserId($userId): array;
}