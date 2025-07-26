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
        return ProjectMember::where('project_id', $projectId)
            ->with('user:id,name,email') // Load user information
            ->get()
            ->map(function ($member) {
                return [
                    'id' => $member->id,
                    'user_id' => $member->user_id,
                    'role' => $member->role,
                    'name' => $member->user->name ?? '',
                    'email' => $member->user->email ?? '',
                ];
            })
            ->toArray();
    }

    public function removeMembersFromProject(int $projectId, array $userIds): bool
    {
        $deleted = ProjectMember::where('project_id', $projectId)
            ->whereIn('user_id', $userIds)
            ->delete();
        
        return $deleted > 0;
    }
} 