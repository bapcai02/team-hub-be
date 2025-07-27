<?php

namespace App\Domain\Project\Repositories;

use App\Domain\Project\Entities\Project;

interface ProjectRepositoryInterface
{
    public function create(array $data): Project;
    public function find($id): ?Project;
    public function all();
    public function getByUserId($userId);
    public function update($id, array $data): ?Project;
    public function delete($id): bool;
    public function getProjectStatistics($id): array;
    public function getTasks($projectId): array;
    public function getDocuments($projectId): array;
    public function getEditHistory($projectId): array;
} 