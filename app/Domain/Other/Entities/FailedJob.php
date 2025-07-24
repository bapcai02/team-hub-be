<?php

namespace App\Domain\Other\Entities;

class FailedJob
{
    public function __construct(
        public int $id,
        public string $uuid,
        public string $connection,
        public string $queue,
        public string $payload,
        public string $exception,
        public string $failed_at,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->connection,
            $model->queue,
            $model->payload,
            $model->exception,
            $model->failed_at,
        );
    }
} 