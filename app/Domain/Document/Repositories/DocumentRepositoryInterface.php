<?php

namespace App\Domain\Document\Repositories;

use App\Domain\Document\Entities\Document;

interface DocumentRepositoryInterface
{
    public function create(array $data): Document;
    public function find($id): ?Document;
    public function getByUserId($userId): array;
    public function getByVisibility($visibility): array;
    public function update($id, array $data): ?Document;
    public function delete($id): bool;
    public function getChildren($parentId): array;
    public function getRootDocuments(): array;
    public function getByProjectId($projectId): array;
} 