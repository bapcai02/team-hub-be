<?php

namespace App\Domain\Report\Entities;

class Report
{
    public function __construct(
        public int $id,
        public string $title,
        public string $type,
        public ?string $filters = null,
        public int $generated_by,
        public string $generated_at,
        public string $file_path,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->title,
            $model->type,
            $model->filters,
            $model->generated_by,
            $model->generated_at,
            $model->file_path,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 