<?php

namespace App\Application\User\Services;

use App\Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;

class AuthService
{
    public function __construct(protected UserRepositoryInterface $userRepository) {}

    public function register(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->create($data);
        $user->notify(new \App\Notifications\WelcomeUserNotification());
        return $user;
    }

    public function login(array $credentials): ?User
    {
        if (!Auth::attempt($credentials)) {
            return null;
        }
        return Auth::user();
    }

    public function resetPassword(array $data): bool
    {
        $user = $this->userRepository->findByEmail($data['email']);
        if (!$user) return false;
        $user->password = Hash::make($data['password']);
        $user->setRememberToken(Str::random(60));
        $user->save();
        return true;
    }
} 