<?php

namespace App\Infrastructure\Persistence\Project\Repositories;

use App\Domain\Project\Repositories\ProjectMemberRepositoryInterface;
use App\Models\ProjectMember;

class ProjectMemberRepository implements ProjectMemberRepositoryInterface
{
    public function addMembersToProject(int $projectId, array $userIds, string $role = 'viewer')
    {
        foreach ($userIds as $userId) {
            ProjectMember::create([
                'project_id' => $projectId,
                'user_id' => $userId,
                'role' => $role,
            ]);
        }
    }

    public function getMembersByProjectId(int $projectId): array
    {
        return ProjectMember::where('project_id', $projectId)->pluck('user_id')->toArray();
    }
} 