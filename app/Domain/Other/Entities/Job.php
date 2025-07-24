<?php

namespace App\Domain\Other\Entities;

class Job
{
    public function __construct(
        public int $id,
        public string $queue,
        public string $payload,
        public int $attempts,
        public ?int $reserved_at = null,
        public int $available_at,
        public int $created_at,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->queue,
            $model->payload,
            $model->attempts,
            $model->reserved_at,
            $model->available_at,
            $model->created_at?->toDateTimeString(),
        );
    }
} 