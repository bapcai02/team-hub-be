<?php

namespace App\Application\User\Services;

use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Entities\User;
use Illuminate\Support\Facades\Hash;

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

    public function create(array $data): User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $this->userRepository->create($data);
    }

    public function update($id, array $data): ?User
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        return $this->userRepository->update($id, $data);
    }

    public function delete($id): bool
    {
        return $this->userRepository->delete($id);
    }

    public function getByStatus($status): array
    {
        return $this->userRepository->getByStatus($status);
    }

    public function getByRole($roleId): array
    {
        return $this->userRepository->getByRole($roleId);
    }

    public function updateLastLogin($id): bool
    {
        return $this->userRepository->updateLastLogin($id);
    }

    public function getActiveUsers(): array
    {
        return $this->userRepository->getActiveUsers();
    }
}
