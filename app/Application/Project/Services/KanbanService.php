<?php

namespace App\Application\Project\Services;

use App\Domain\Project\Repositories\KanbanColumnRepositoryInterface;
use App\Domain\Project\Entities\KanbanColumn;

class KanbanService
{
    public function __construct(
        protected KanbanColumnRepositoryInterface $kanbanColumnRepository,
    ) {}

    public function createColumn(array $data): KanbanColumn
    {
        return $this->kanbanColumnRepository->create($data);
    }

    public function findColumn($id): ?KanbanColumn
    {
        return $this->kanbanColumnRepository->find($id);
    }

    public function getColumnsByProject($projectId): array
    {
        return $this->kanbanColumnRepository->getByProjectId($projectId);
    }

    public function updateColumn($id, array $data): ?KanbanColumn
    {
        return $this->kanbanColumnRepository->update($id, $data);
    }

    public function deleteColumn($id): bool
    {
        return $this->kanbanColumnRepository->delete($id);
    }

    public function reorderColumns($projectId, array $columnIds): bool
    {
        return $this->kanbanColumnRepository->reorder($projectId, $columnIds);
    }

    public function initializeDefaultColumns($projectId): array
    {
        return $this->kanbanColumnRepository->getDefaultColumns($projectId);
    }

    public function getProjectTasks($projectId): array
    {
        return \App\Models\Task::where('project_id', $projectId)
            ->with(['assignedUser', 'createdByUser'])
            ->get()
            ->toArray();
    }
} 