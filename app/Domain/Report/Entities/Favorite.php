<?php

namespace App\Domain\Report\Entities;

class Favorite
{
    public function __construct(
        public int $id,
        public int $user_id,
        public string $type,
        public int $target_id,
        public ?string $created_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->user_id,
            $model->item_type,
            $model->item_id,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 