<?php

namespace App\Domain\Other\Entities;

class Tenant
{
    public function __construct(
        public int $id,
        public string $name,
        public string $code,
        public string $database_schema,
        public bool $is_active = true,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->name,
            $model->domain,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 