<?php

namespace App\Domain\Document\Entities;

class DocumentBlock
{
    public function __construct(
        public int $id,
        public int $document_id,
        public string $type,
        public ?string $content = null,
        public int $order = 0,
        public string $created_at,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->document_id,
            $model->content,
            $model->order,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 