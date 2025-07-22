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
}
