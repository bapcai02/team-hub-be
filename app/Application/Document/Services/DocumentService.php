<?php

namespace App\Application\Document\Services;

use App\Domain\Document\Repositories\DocumentRepositoryInterface;
use App\Domain\Document\Entities\Document;

class DocumentService
{
    public function __construct(
        protected DocumentRepositoryInterface $documentRepository,
    ) {}

    public function create(array $data): Document
    {
        return $this->documentRepository->create($data);
    }

    public function find($id): ?Document
    {
        return $this->documentRepository->find($id);
    }

    public function getByUserId($userId): array
    {
        return $this->documentRepository->getByUserId($userId);
    }

    public function getByVisibility($visibility): array
    {
        return $this->documentRepository->getByVisibility($visibility);
    }

    public function update($id, array $data): ?Document
    {
        return $this->documentRepository->update($id, $data);
    }

    public function delete($id): bool
    {
        return $this->documentRepository->delete($id);
    }

    public function getChildren($parentId): array
    {
        return $this->documentRepository->getChildren($parentId);
    }

    public function getRootDocuments(): array
    {
        return $this->documentRepository->getRootDocuments();
    }

    public function getByProjectId($projectId): array
    {
        return $this->documentRepository->getByProjectId($projectId);
    }
} 