<?php

namespace App\Infrastructure\Persistence\Project\Repositories;

use App\Domain\Project\Repositories\TaskRepositoryInterface;
use App\Domain\Project\Entities\Task;
use App\Models\Task as TaskModel;

class TaskRepository implements TaskRepositoryInterface
{
    public function create(array $data): Task
    {
        $model = TaskModel::create($data);
        return Task::fromModel($model);
    }

    public function find($id): ?Task
    {
        $model = TaskModel::find($id);
        return $model ? Task::fromModel($model) : null;
    }

    public function getByProjectId($projectId): array
    {
        $tasks = TaskModel::where('project_id', $projectId)->get();
        return $tasks->map(fn($model) => Task::fromModel($model))->all();
    }

    public function update($id, array $data): ?Task
    {
        $model = TaskModel::find($id);
        if (!$model) {
            return null;
        }
        $model->update($data);
        return Task::fromModel($model);
    }

    public function delete($id): bool
    {
        $model = TaskModel::find($id);
        if (!$model) {
            return false;
        }
        $model->delete();
        return true;
    }

    public function getByAssignee($userId): array
    {
        $tasks = TaskModel::where('assigned_to', $userId)->get();
        return $tasks->map(fn($model) => Task::fromModel($model))->all();
    }

    public function getByStatus($projectId, $status): array
    {
        $tasks = TaskModel::where('project_id', $projectId)
            ->where('status', $status)
            ->get();
        return $tasks->map(fn($model) => Task::fromModel($model))->all();
    }
} 