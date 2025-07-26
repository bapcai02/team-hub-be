<?php

namespace App\Infrastructure\Persistence\Project\Repositories;

use App\Domain\Project\Repositories\KanbanColumnRepositoryInterface;
use App\Domain\Project\Entities\KanbanColumn;
use App\Models\KanbanColumn as KanbanColumnModel;

class KanbanColumnRepository implements KanbanColumnRepositoryInterface
{
    public function create(array $data): KanbanColumn
    {
        $model = KanbanColumnModel::create($data);
        return KanbanColumn::fromModel($model);
    }

    public function find($id): ?KanbanColumn
    {
        $model = KanbanColumnModel::find($id);
        return $model ? KanbanColumn::fromModel($model) : null;
    }

    public function getByProjectId($projectId): array
    {
        $columns = KanbanColumnModel::where('project_id', $projectId)
            ->orderBy('order')
            ->get();
        return $columns->map(fn($model) => KanbanColumn::fromModel($model))->all();
    }

    public function update($id, array $data): ?KanbanColumn
    {
        $model = KanbanColumnModel::find($id);
        if (!$model) {
            return null;
        }
        $model->update($data);
        return KanbanColumn::fromModel($model);
    }

    public function delete($id): bool
    {
        $model = KanbanColumnModel::find($id);
        if (!$model) {
            return false;
        }
        $model->delete();
        return true;
    }

    public function reorder($projectId, array $columnIds): bool
    {
        foreach ($columnIds as $order => $columnId) {
            KanbanColumnModel::where('id', $columnId)
                ->where('project_id', $projectId)
                ->update(['order' => $order]);
        }
        return true;
    }

    public function getDefaultColumns($projectId): array
    {
        $defaultColumns = [
            ['name' => 'Backlog', 'order' => 0],
            ['name' => 'To Do', 'order' => 1],
            ['name' => 'In Progress', 'order' => 2],
            ['name' => 'Review', 'order' => 3],
            ['name' => 'Done', 'order' => 4],
        ];

        $columns = [];
        foreach ($defaultColumns as $columnData) {
            $columnData['project_id'] = $projectId;
            $model = KanbanColumnModel::create($columnData);
            $columns[] = KanbanColumn::fromModel($model);
        }

        return $columns;
    }
} 