<?php

namespace App\Domain\User\Entities;

class EmployeeEntity
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
        public $user = null,
        public $department = null,
    ) {}

    public static function fromModel($model): self
    {
        $user = $model->user ? 
            \App\Domain\User\Entities\User::fromModel($model->user) : null;
        $department = $model->department ? 
            \App\Domain\User\Entities\Department::fromModel($model->department) : null;
        
        return new self(
            $model->id,
            $model->user_id,
            $model->department_id,
            $model->position,
            (float) $model->salary,
            $model->contract_type,
            $model->hire_date ? (is_string($model->hire_date) ? $model->hire_date : $model->hire_date->toDateString()) : '',
            $model->dob ? (is_string($model->dob) ? $model->dob : $model->dob->toDateString()) : '',
            $model->gender,
            $model->phone,
            $model->address,
            $model->created_at ? (is_string($model->created_at) ? $model->created_at : $model->created_at->toDateTimeString()) : null,
            $model->updated_at ? (is_string($model->updated_at) ? $model->updated_at : $model->updated_at->toDateTimeString()) : null,
            $model->deleted_at ? (is_string($model->deleted_at) ? $model->deleted_at : $model->deleted_at->toDateTimeString()) : null,
            $user,
            $department,
        );
    }
} 