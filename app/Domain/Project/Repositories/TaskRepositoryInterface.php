<?php

namespace App\Domain\Project\Repositories;

use App\Domain\Project\Entities\Task;

interface TaskRepositoryInterface
{
    public function create(array $data): Task;
    public function find($id): ?Task;
    public function getByProjectId($projectId): array;
    public function update($id, array $data): ?Task;
    public function delete($id): bool;
    public function getByAssignee($userId): array;
    public function getByStatus($projectId, $status): array;
} 