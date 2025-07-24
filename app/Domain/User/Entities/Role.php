<?php

namespace App\Domain\User\Entities;

class Role
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $created_at = null,
        public ?string $updated_at = null,
        public ?string $deleted_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->name,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 