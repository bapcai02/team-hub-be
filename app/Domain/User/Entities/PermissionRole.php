<?php

namespace App\Domain\User\Entities;

class PermissionRole
{
    public function __construct(
        public int $id,
        public int $permission_id,
        public int $role_id,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->permission_id,
            $model->role_id,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 