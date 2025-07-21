<?php

namespace App\Domain\Report\Entities;

class Webhook
{
    public function __construct(
        public int $id,
        public string $name,
        public string $url,
        public string $event,
        public string $secret,
        public bool $is_active = true,
        public int $created_by,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->url,
            $model->event,
            $model->secret,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 