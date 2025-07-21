<?php

namespace App\Domain\Report\Entities;

class Widget
{
    public function __construct(
        public int $id,
        public int $user_id,
        public int $dashboard_id,
        public string $type,
        public string $config,
        public int $order = 0,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->name,
            $model->type,
            $model->settings,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 