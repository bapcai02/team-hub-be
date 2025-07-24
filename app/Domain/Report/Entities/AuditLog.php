<?php

namespace App\Domain\Report\Entities;

class AuditLog
{
    public function __construct(
        public int $id,
        public int $user_id,
        public string $action,
        public string $target_table,
        public int $target_id,
        public ?string $data = null,
        public ?string $ip_address = null,
        public ?string $user_agent = null,
        public ?string $created_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->user_id,
            $model->action,
            $model->ip_address,
            $model->user_agent,
            $model->created_at?->toDateTimeString(),
        );
    }
} 