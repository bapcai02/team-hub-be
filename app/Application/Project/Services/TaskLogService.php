<?php

namespace App\Application\Project\Services;

use App\Domain\Project\Repositories\TaskLogRepositoryInterface;
use App\Domain\Project\Entities\TaskLog;
use Carbon\Carbon;

class TaskLogService
{
    public function __construct(
        protected TaskLogRepositoryInterface $taskLogRepository,
    ) {}

    public function create(array $data): TaskLog
    {
        return $this->taskLogRepository->create($data);
    }

    public function find($id): ?TaskLog
    {
        return $this->taskLogRepository->find($id);
    }

    public function getByTaskId($taskId): array
    {
        return $this->taskLogRepository->getByTaskId($taskId);
    }

    public function getByUserId($userId): array
    {
        return $this->taskLogRepository->getByUserId($userId);
    }

    public function update($id, array $data): ?TaskLog
    {
        return $this->taskLogRepository->update($id, $data);
    }

    public function delete($id): bool
    {
        return $this->taskLogRepository->delete($id);
    }

    public function startTime($taskId, $userId, $note = null): TaskLog
    {
        // Check if there's already an active log
        $activeLog = $this->taskLogRepository->getActiveLog($taskId, $userId);
        if ($activeLog) {
            throw new \Exception('Already have an active time log for this task');
        }

        $data = [
            'task_id' => $taskId,
            'user_id' => $userId,
            'start_time' => now(),
            'note' => $note,
        ];

        return $this->taskLogRepository->create($data);
    }

    public function stopTime($taskId, $userId): TaskLog
    {
        $activeLog = $this->taskLogRepository->getActiveLog($taskId, $userId);
        if (!$activeLog) {
            throw new \Exception('No active time log found for this task');
        }

        $endTime = now();
        $startTime = Carbon::parse($activeLog->start_time);
        $duration = $startTime->diffInMinutes($endTime);

        $data = [
            'end_time' => $endTime,
            'duration' => $duration,
        ];

        return $this->taskLogRepository->update($activeLog->id, $data);
    }

    public function getActiveLog($taskId, $userId): ?TaskLog
    {
        return $this->taskLogRepository->getActiveLog($taskId, $userId);
    }

    public function getTotalTimeByTask($taskId): int
    {
        return $this->taskLogRepository->getTotalTimeByTask($taskId);
    }

    public function getTotalTimeByUser($userId): int
    {
        return $this->taskLogRepository->getTotalTimeByUser($userId);
    }
} 