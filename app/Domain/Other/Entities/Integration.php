<?php

namespace App\Domain\Other\Entities;

class Integration
{
    public function __construct(
        public int $id,
        public string $name,
        public string $type,
        public string $config,
        public string $status,
        public int $created_by,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->name,
            $model->type,
            $model->settings,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 