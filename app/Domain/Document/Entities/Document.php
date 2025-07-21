<?php

namespace App\Domain\Document\Entities;

class Document
{
    public function __construct(
        public int $id,
        public string $title,
        public ?int $parent_id = null,
        public int $created_by,
        public string $visibility,
        public ?string $created_at = null,
        public ?string $updated_at = null,
        public ?string $deleted_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->title,
            $model->parent_id,
            $model->created_by,
            $model->visibility,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
            $model->deleted_at?->toDateTimeString(),
        );
    }
} 