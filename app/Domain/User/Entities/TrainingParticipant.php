<?php

namespace App\Domain\User\Entities;

class TrainingParticipant
{
    public function __construct(
        public int $id,
        public int $training_id,
        public int $employee_id,
        public string $status,
        public ?float $score = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->training_id,
            $model->employee_id,
            $model->status,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 