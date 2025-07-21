<?php

namespace App\Domain\Report\Entities;

class ReportTemplate
{
    public function __construct(
        public int $id,
        public string $name,
        public string $type,
        public string $structure,
        public int $created_by,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->name,
            $model->content,
            $model->created_by,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 