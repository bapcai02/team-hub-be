<?php

namespace App\Domain\Other\Entities;

class Currency
{
    public function __construct(
        public int $id,
        public string $code,
        public string $name,
        public string $symbol,
        public bool $is_default = false,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->code,
            $model->name,
            $model->symbol,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 