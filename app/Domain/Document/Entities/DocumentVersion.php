<?php

namespace App\Domain\Document\Entities;

class DocumentVersion
{
    public function __construct(
        public int $id,
        public int $document_id,
        public string $content_snapshot,
        public string $hash,
        public int $created_by,
        public string $created_at,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->document_id,
            $model->version,
            $model->content,
            $model->created_by,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 