<?php

namespace App\Domain\Project\Repositories;

use App\Domain\Project\Entities\TaskLog;

interface TaskLogRepositoryInterface
{
    public function create(array $data): TaskLog;
    public function find($id): ?TaskLog;
    public function getByTaskId($taskId): array;
    public function getByUserId($userId): array;
    public function update($id, array $data): ?TaskLog;
    public function delete($id): bool;
    public function getActiveLog($taskId, $userId): ?TaskLog;
    public function getTotalTimeByTask($taskId): int;
    public function getTotalTimeByUser($userId): int;
} 