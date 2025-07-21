<?php

namespace App\Domain\Other\Entities;

class SystemSetting
{
    public function __construct(
        public int $id,
        public string $key,
        public string $value,
        public string $type,
        public string $updated_at,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->key,
            $model->value,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 