<?php

namespace App\Application\Project\Services;

use App\Domain\Project\Entities\Project as ProjectEntity;
use App\Domain\Project\Repositories\ProjectRepositoryInterface;
use App\Domain\Project\Repositories\ProjectMemberRepositoryInterface;

class ProjectService
{
    public function __construct(
        protected ProjectRepositoryInterface $projectRepository,
        protected ProjectMemberRepositoryInterface $projectMemberRepository,
    ) {}

    public function createProject(array $data): ProjectEntity
    {
        return $this->projectRepository->create($data);
    }

    public function createProjectWithMembers(array $data, array $members = []): ProjectEntity
    {
        $project = $this->projectRepository->create($data);
        if (!empty($members)) {
            $this->projectMemberRepository->addMembersToProject($project->id, $members);
        }
        return $project;
    }

    public function getAllProjects()
    {
        return $this->projectRepository->all();
    }

    public function getProjectsByUserId($userId)
    {
        return $this->projectRepository->getByUserId($userId);
    }

    public function find($id): ?ProjectEntity
    {
        return $this->projectRepository->find($id);
    }

    public function findWithMembers($id): ?array
    {
        $project = $this->projectRepository->find($id);
        if (!$project) {
            return null;
        }
        
        $members = $this->projectMemberRepository->getMembersByProjectId($id);
        
        return [
            'project' => $project,
            'members' => $members
        ];
    }

    public function update($id, array $data): ?ProjectEntity
    {
        return $this->projectRepository->update($id, $data);
    }

    public function delete($id): bool
    {
        return $this->projectRepository->delete($id);
    }

    public function getProjectStatistics($id): array
    {
        return $this->projectRepository->getProjectStatistics($id);
    }

    public function addMembersToProject(int $projectId, array $memberIds, string $role = 'member'): void
    {
        $this->projectMemberRepository->addMembersToProject($projectId, $memberIds, $role);
    }

    public function removeMembersFromProject(int $projectId, array $memberIds): bool
    {
        return $this->projectMemberRepository->removeMembersFromProject($projectId, $memberIds);
    }

    public function getProjectMembers(int $projectId): array
    {
        return $this->projectMemberRepository->getMembersByProjectId($projectId);
    }
} 