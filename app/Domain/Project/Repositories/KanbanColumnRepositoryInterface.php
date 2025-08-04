<?php

namespace App\Domain\Project\Repositories;

use App\Domain\Project\Entities\KanbanColumn;

interface KanbanColumnRepositoryInterface
{
    public function create(array $data): KanbanColumn;
    public function find($id): ?KanbanColumn;
    public function getByProjectId($projectId): array;
    public function update($id, array $data): ?KanbanColumn;
    public function delete($id): bool;
    public function reorder($projectId, array $columnIds): bool;
    public function getDefaultColumns($projectId): array;
} 