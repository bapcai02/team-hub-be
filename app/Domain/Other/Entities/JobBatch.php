<?php

namespace App\Domain\Other\Entities;

class JobBatch
{
    public function __construct(
        public string $id,
        public string $name,
        public int $total_jobs,
        public int $pending_jobs,
        public int $failed_jobs,
        public string $failed_job_ids,
        public ?string $options = null,
        public ?int $cancelled_at = null,
        public int $created_at,
        public ?int $finished_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->name,
            $model->total_jobs,
            $model->pending_jobs,
            $model->failed_jobs,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 