<?php

namespace App\Domain\Other\Entities;

class ApiToken
{
    public function __construct(
        public int $id,
        public int $user_id,
        public string $token,
        public ?string $last_used_at = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
        public ?string $deleted_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->user_id,
            $model->token,
            $model->expires_at,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 