<?php

namespace App\Domain\User\Entities;

class User
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?int $role_id = null,
        public string $status = 'active',
        public ?string $last_login_at = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
        public ?string $deleted_at = null,
        public $employee = null,
    ) {}

    public static function fromModel($model): self
    {
        $employee = $model->employee ? 
            \App\Domain\User\Entities\Employee::fromModel($model->employee) : null;
        return new self(
            $model->id,
            $model->name,
            $model->email,
            $model->role_id ?? null,
            $model->status ?? 'active',
            $model->last_login_at?->toDateTimeString(),
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
            $model->deleted_at?->toDateTimeString(),
            $employee,
        );
    }
} 