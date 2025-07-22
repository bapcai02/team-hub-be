<?php

namespace App\Domain\User\Entities;

class Department
{
    public function __construct(
        public int $id,
        public string $name,
        public ?int $manager_id = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
        public ?string $deleted_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->name,
            isset($model->manager_id) ? (is_null($model->manager_id) ? null : (int)$model->manager_id) : null,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
            $model->deleted_at?->toDateTimeString(),
        );
    }
} 