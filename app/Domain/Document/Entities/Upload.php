<?php

namespace App\Domain\Document\Entities;

class Upload
{
    public function __construct(
        public int $id,
        public int $uploaded_by,
        public string $file_path,
        public string $original_name,
        public string $mime_type,
        public int $size,
        public string $related_type,
        public int $related_id,
        public string $created_at,
        public ?string $deleted_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->file_path,
            $model->file_type,
            $model->uploaded_by,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 