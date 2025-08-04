<?php

namespace App\Infrastructure\Persistence\Project\Repositories;

use App\Domain\Project\Repositories\TaskCommentRepositoryInterface;
use App\Domain\Project\Entities\TaskComment;
use App\Models\TaskComment as TaskCommentModel;

class TaskCommentRepository implements TaskCommentRepositoryInterface
{
    public function create(array $data): TaskComment
    {
        $model = TaskCommentModel::create($data);
        return TaskComment::fromModel($model);
    }

    public function find($id): ?TaskComment
    {
        $model = TaskCommentModel::find($id);
        return $model ? TaskComment::fromModel($model) : null;
    }

    public function getByTaskId($taskId): array
    {
        $comments = TaskCommentModel::where('task_id', $taskId)
            ->orderBy('created_at', 'desc')
            ->get();
        return $comments->map(fn($model) => TaskComment::fromModel($model))->all();
    }

    public function update($id, array $data): ?TaskComment
    {
        $model = TaskCommentModel::find($id);
        if (!$model) {
            return null;
        }
        $model->update($data);
        return TaskComment::fromModel($model);
    }

    public function delete($id): bool
    {
        $model = TaskCommentModel::find($id);
        if (!$model) {
            return false;
        }
        $model->delete();
        return true;
    }

    public function getByUserId($userId): array
    {
        $comments = TaskCommentModel::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        return $comments->map(fn($model) => TaskComment::fromModel($model))->all();
    }
}