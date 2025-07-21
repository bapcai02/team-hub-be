<?php

namespace App\Domain\Report\Entities;

class SearchHistory
{
    public function __construct(
        public int $id,
        public int $user_id,
        public string $query,
        public ?string $filters = null,
        public ?string $created_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->user_id,
            $model->query,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 