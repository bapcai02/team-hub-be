<?php

namespace App\Infrastructure\Persistence\User\Repositories;

use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Entities\User as UserEntity;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): UserEntity
    {
        $model = User::create($data);
        return UserEntity::fromModel($model);
    }

    public function findByEmail(string $email): ?UserEntity
    {
        $model = User::where('email', $email)->first();
        return $model ? UserEntity::fromModel($model) : null;
    }

    public function all()
    {
        return User::with(['employee.department', 'employee.skills'])->get()->map(fn($model) => \App\Domain\User\Entities\User::fromModel($model));
    }

    public function find($id): ?UserEntity
    {
        $model = User::find($id);
        return $model ? UserEntity::fromModel($model) : null;
    }

    public function update($id, array $data): ?UserEntity
    {
        $model = User::find($id);
        if (!$model) {
            return null;
        }
        $model->update($data);
        return UserEntity::fromModel($model);
    }

    public function delete($id): bool
    {
        $model = User::find($id);
        if (!$model) {
            return false;
        }
        $model->delete();
        return true;
    }

    public function getByStatus($status): array
    {
        $users = User::where('status', $status)->get();
        return $users->map(fn($model) => UserEntity::fromModel($model))->all();
    }

    public function getByRole($roleId): array
    {
        $users = User::where('role_id', $roleId)->get();
        return $users->map(fn($model) => UserEntity::fromModel($model))->all();
    }

    public function updateLastLogin($id): bool
    {
        $model = User::find($id);
        if (!$model) {
            return false;
        }
        $model->update(['last_login_at' => now()]);
        return true;
    }

    public function getActiveUsers(): array
    {
        $users = User::where('status', 'active')->get();
        return $users->map(fn($model) => UserEntity::fromModel($model))->all();
    }
}
