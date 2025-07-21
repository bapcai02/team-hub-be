<?php

namespace App\Infrastructure\Persistence\Project\Repositories;

use App\Domain\Project\Repositories\ProjectRepositoryInterface;
use App\Domain\Project\Entities\Project as ProjectEntity;
use App\Models\Project;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function create(array $data): ProjectEntity
    {
        $model = Project::create($data);
        return ProjectEntity::fromModel($model);
    }

    public function find($id): ?ProjectEntity
    {
        $model = Project::find($id);
        return $model ? ProjectEntity::fromModel($model) : null;
    }

    public function all()
    {
        return Project::all()->map(fn($model) => ProjectEntity::fromModel($model));
    }

    public function getByUserId($userId)
    {
        $projectIds = \App\Models\ProjectMember::where('user_id', $userId)->pluck('project_id');
        return \App\Models\Project::whereIn('id', $projectIds)->get()->map(fn($model) => ProjectEntity::fromModel($model));
    }
} 