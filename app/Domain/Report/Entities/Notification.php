<?php

namespace App\Domain\Report\Entities;

class Notification
{
    public function __construct(
        public int $id,
        public int $user_id,
        public string $type,
        public ?string $data = null,
        public ?string $read_at = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->user_id,
            $model->type,
            $model->data,
            $model->read_at,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 