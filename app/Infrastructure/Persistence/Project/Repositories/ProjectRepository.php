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
        $projects = \App\Models\Project::whereIn('id', $projectIds)->get();
        return $projects->map(function($model) {
            $totalTasks = \App\Models\Task::where('project_id', $model->id)->count();
            $totalMembers = \App\Models\ProjectMember::where('project_id', $model->id)->count();
            return \App\Domain\Project\Entities\Project::fromModel($model, $totalTasks, $totalMembers);
        });
    }

    public function update($id, array $data): ?ProjectEntity
    {
        $model = Project::find($id);
        if (!$model) {
            return null;
        }
        $model->update($data);
        return ProjectEntity::fromModel($model);
    }

    public function delete($id): bool
    {
        $model = Project::find($id);
        if (!$model) {
            return false;
        }
        $model->delete();
        return true;
    }

    public function getProjectStatistics($id): array
    {
        $project = Project::find($id);
        if (!$project) {
            return [];
        }

        $totalTasks = \App\Models\Task::where('project_id', $id)->count();
        $completedTasks = \App\Models\Task::where('project_id', $id)->where('status', 'completed')->count();
        $pendingTasks = \App\Models\Task::where('project_id', $id)->where('status', 'pending')->count();
        $overdueTasks = \App\Models\Task::where('project_id', $id)->where('due_date', '<', now())->where('status', '!=', 'completed')->count();
        $totalMembers = \App\Models\ProjectMember::where('project_id', $id)->count();

        $progressPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0;

        return [
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'pending_tasks' => $pendingTasks,
            'overdue_tasks' => $overdueTasks,
            'total_members' => $totalMembers,
            'progress_percentage' => $progressPercentage,
            'days_remaining' => $project->end_date ? now()->diffInDays($project->end_date, false) : 0,
        ];
    }
} 