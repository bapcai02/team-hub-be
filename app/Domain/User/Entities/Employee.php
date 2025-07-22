<?php

namespace App\Domain\User\Entities;

class Employee
{
    public function __construct(
        public int $id,
        public int $user_id,
        public int $department_id,
        public string $position,
        public float $salary,
        public string $contract_type,
        public string $hire_date,
        public string $dob,
        public string $gender,
        public string $phone,
        public string $address,
        public ?string $created_at = null,
        public ?string $updated_at = null,
        public ?string $deleted_at = null,
        public $department = null,
        public array $skills = [],
    ) {}

    public static function fromModel($model): self
    {
        $department = $model->department ?
            \App\Domain\User\Entities\Department::fromModel($model->department) : null;
        $skills = $model->skills ? $model->skills->map(fn($skill) =>
            \App\Domain\User\Entities\Skill::fromModel($skill))->toArray() : [];
        return new self(
            $model->id,
            $model->user_id,
            $model->department_id,
            $model->position,
            $model->salary,
            $model->contract_type,
            $model->hire_date,
            $model->dob,
            $model->gender,
            $model->phone,
            $model->address,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
            $model->deleted_at?->toDateTimeString(),
            $department,
            $skills,
        );
    }
} 