<?php

namespace App\Domain\User\Entities;

class RoleUser
{
    public function __construct(
        public int $id,
        public int $role_id,
        public int $user_id,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->role_id,
            $model->user_id,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 