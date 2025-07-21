<?php

namespace App\Domain\Other\Entities;

class LoginActivity
{
    public function __construct(
        public int $id,
        public int $user_id,
        public ?string $ip_address = null,
        public ?string $device = null,
        public ?string $login_at = null,
        public ?string $logout_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->user_id,
            $model->ip_address,
            $model->user_agent,
            $model->login_at,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 