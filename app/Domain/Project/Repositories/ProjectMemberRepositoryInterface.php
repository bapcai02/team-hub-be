<?php

namespace App\Domain\Project\Repositories;

interface ProjectMemberRepositoryInterface
{
    public function addMembersToProject(int $projectId, array $userIds, string $role = 'viewer');
    public function getMembersByProjectId(int $projectId): array;
    public function removeMembersFromProject(int $projectId, array $userIds): bool;
} 