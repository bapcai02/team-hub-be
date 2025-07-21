<?php

namespace App\Domain\User\Entities;

class EmployeeSkill
{
    public function __construct(
        public int $id,
        public int $employee_id,
        public int $skill_id,
        public int $level,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->employee_id,
            $model->skill_id,
            $model->level,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 