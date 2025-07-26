<?php

namespace App\Infrastructure\Persistence\Project\Repositories;

use App\Domain\Project\Repositories\TaskLogRepositoryInterface;
use App\Domain\Project\Entities\TaskLog;
use App\Models\TaskLog as TaskLogModel;

class TaskLogRepository implements TaskLogRepositoryInterface
{
    public function create(array $data): TaskLog
    {
        $model = TaskLogModel::create($data);
        return TaskLog::fromModel($model);
    }

    public function find($id): ?TaskLog
    {
        $model = TaskLogModel::find($id);
        return $model ? TaskLog::fromModel($model) : null;
    }

    public function getByTaskId($taskId): array
    {
        $logs = TaskLogModel::where('task_id', $taskId)->get();
        return $logs->map(fn($model) => TaskLog::fromModel($model))->all();
    }

    public function getByUserId($userId): array
    {
        $logs = TaskLogModel::where('user_id', $userId)->get();
        return $logs->map(fn($model) => TaskLog::fromModel($model))->all();
    }

    public function update($id, array $data): ?TaskLog
    {
        $model = TaskLogModel::find($id);
        if (!$model) {
            return null;
        }
        $model->update($data);
        return TaskLog::fromModel($model);
    }

    public function delete($id): bool
    {
        $model = TaskLogModel::find($id);
        if (!$model) {
            return false;
        }
        $model->delete();
        return true;
    }

    public function getActiveLog($taskId, $userId): ?TaskLog
    {
        $model = TaskLogModel::where('task_id', $taskId)
            ->where('user_id', $userId)
            ->whereNotNull('start_time')
            ->whereNull('end_time')
            ->first();
        return $model ? TaskLog::fromModel($model) : null;
    }

    public function getTotalTimeByTask($taskId): int
    {
        return TaskLogModel::where('task_id', $taskId)
            ->whereNotNull('end_time')
            ->sum('duration');
    }

    public function getTotalTimeByUser($userId): int
    {
        return TaskLogModel::where('user_id', $userId)
            ->whereNotNull('end_time')
            ->sum('duration');
    }
} 