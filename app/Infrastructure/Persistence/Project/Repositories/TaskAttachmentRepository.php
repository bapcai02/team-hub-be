<?php

namespace App\Infrastructure\Persistence\Project\Repositories;

use App\Domain\Project\Repositories\TaskAttachmentRepositoryInterface;
use App\Domain\Project\Entities\TaskAttachment;
use App\Models\TaskAttachment as TaskAttachmentModel;

class TaskAttachmentRepository implements TaskAttachmentRepositoryInterface
{
    public function create(array $data): TaskAttachment
    {
        $model = TaskAttachmentModel::create($data);
        return TaskAttachment::fromModel($model);
    }

    public function find($id): ?TaskAttachment
    {
        $model = TaskAttachmentModel::find($id);
        return $model ? TaskAttachment::fromModel($model) : null;
    }

    public function getByTaskId($taskId): array
    {
        $attachments = TaskAttachmentModel::where('task_id', $taskId)
            ->orderBy('created_at', 'desc')
            ->get();
        return $attachments->map(fn($model) => TaskAttachment::fromModel($model))->all();
    }

    public function delete($id): bool
    {
        $model = TaskAttachmentModel::find($id);
        if (!$model) {
            return false;
        }
        $model->delete();
        return true;
    }

    public function getByUserId($userId): array
    {
        $attachments = TaskAttachmentModel::where('uploaded_by', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        return $attachments->map(fn($model) => TaskAttachment::fromModel($model))->all();
    }
}