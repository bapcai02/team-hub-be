<?php

namespace App\Domain\User\Entities;

class Training
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $description = null,
        public int $trainer_id,
        public string $start_date,
        public string $end_date,
        public string $location,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->title,
            $model->description,
            $model->start_date,
            $model->end_date,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 