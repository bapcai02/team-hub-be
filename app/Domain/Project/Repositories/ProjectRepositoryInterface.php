<?php

namespace App\Domain\Project\Repositories;

use App\Domain\Project\Entities\Project;

interface ProjectRepositoryInterface
{
    public function create(array $data): Project;
    public function find($id): ?Project;
    public function all();
    public function getByUserId($userId);
} 