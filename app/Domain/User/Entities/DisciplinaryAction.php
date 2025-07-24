<?php

namespace App\Domain\User\Entities;

class DisciplinaryAction
{
    public function __construct(
        public int $id,
        public int $employee_id,
        public string $type,
        public string $reason,
        public string $date,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->employee_id,
            $model->action,
            $model->reason,
            $model->date,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 