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
} 