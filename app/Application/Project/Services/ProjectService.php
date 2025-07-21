<?php

namespace App\Application\Project\Services;

use App\Domain\Project\Entities\Project as ProjectEntity;
use App\Domain\Project\Repositories\ProjectRepositoryInterface;
use App\Models\Project;

class ProjectService
{
    public function __construct(protected ProjectRepositoryInterface $projectRepository) {}

    public function createProject(array $data): ProjectEntity
    {
        return $this->projectRepository->create($data);
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