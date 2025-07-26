<?php

namespace App\Infrastructure\Persistence\Document\Repositories;

use App\Domain\Document\Repositories\DocumentRepositoryInterface;
use App\Domain\Document\Entities\Document;
use App\Models\Document as DocumentModel;

class DocumentRepository implements DocumentRepositoryInterface
{
    public function create(array $data): Document
    {
        $model = DocumentModel::create($data);
        return Document::fromModel($model);
    }

    public function find($id): ?Document
    {
        $model = DocumentModel::find($id);
        return $model ? Document::fromModel($model) : null;
    }

    public function getByUserId($userId): array
    {
        $documents = DocumentModel::where('created_by', $userId)->get();
        return $documents->map(fn($model) => Document::fromModel($model))->all();
    }

    public function getByVisibility($visibility): array
    {
        $documents = DocumentModel::where('visibility', $visibility)->get();
        return $documents->map(fn($model) => Document::fromModel($model))->all();
    }

    public function update($id, array $data): ?Document
    {
        $model = DocumentModel::find($id);
        if (!$model) {
            return null;
        }
        $model->update($data);
        return Document::fromModel($model);
    }

    public function delete($id): bool
    {
        $model = DocumentModel::find($id);
        if (!$model) {
            return false;
        }
        $model->delete();
        return true;
    }

    public function getChildren($parentId): array
    {
        $documents = DocumentModel::where('parent_id', $parentId)->get();
        return $documents->map(fn($model) => Document::fromModel($model))->all();
    }

    public function getRootDocuments(): array
    {
        $documents = DocumentModel::whereNull('parent_id')->get();
        return $documents->map(fn($model) => Document::fromModel($model))->all();
    }
} 