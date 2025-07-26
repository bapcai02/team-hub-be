<?php

namespace App\Domain\User\Repositories;

use App\Domain\User\Entities\User;

interface UserRepositoryInterface
{
    public function create(array $data): User;
    public function findByEmail(string $email): ?User;
    public function all();
    public function find($id): ?User;
    public function update($id, array $data): ?User;
    public function delete($id): bool;
    public function getByStatus($status): array;
    public function getByRole($roleId): array;
    public function updateLastLogin($id): bool;
    public function getActiveUsers(): array;
}
