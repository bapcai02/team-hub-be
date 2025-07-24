<?php

namespace App\Domain\Report\Entities;

class DashboardLayout
{
    public function __construct(
        public int $id,
        public int $user_id,
        public string $name,
        public string $layout_config,
        public bool $is_default = false,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->user_id,
            $model->layout,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 