<?php

namespace App\Domain\Project\Entities;

class ProjectMember
{
    public function __construct(
        public int $id,
        public int $project_id,
        public int $user_id,
        public string $role,
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->project_id,
            $model->user_id,
            $model->role,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 