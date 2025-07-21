<?php

namespace App\Domain\Other\Entities;

class TenantUser
{
    public function __construct(
        public int $id,
        public int $tenant_id,
        public int $user_id,
        public string $role,
        public string $joined_at,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->tenant_id,
            $model->user_id,
            $model->role,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 