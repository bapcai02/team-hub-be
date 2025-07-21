<?php

namespace App\Domain\User\Entities;

class User
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public string $password,
        public ?int $role_id = null,
        public string $status = 'active',
        public ?string $last_login_at = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
        public ?string $deleted_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->name,
            $model->email,
            $model->password,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 