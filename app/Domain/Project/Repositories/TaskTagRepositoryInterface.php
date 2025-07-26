<?php

namespace App\Domain\Project\Repositories;

use App\Domain\Project\Entities\TaskTag;

interface TaskTagRepositoryInterface
{
    public function create(array $data): TaskTag;
    public function find($id): ?TaskTag;
    public function getAll(): array;
    public function update($id, array $data): ?TaskTag;
    public function delete($id): bool;
    public function getByTaskId($taskId): array;
}