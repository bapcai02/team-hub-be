<?php

namespace App\Application\User\Services;

use App\Domain\User\Repositories\UserRepositoryInterface;

class UserService
{
    public function __construct(protected UserRepositoryInterface $userRepository) {}

    public function getAll()
    {
        return $this->userRepository->all();
    }

    public function getById($id)
    {
        return $this->userRepository->find($id);
    }
}
