<?php

namespace App\Interfaces\Http\Controllers;

use App\Interfaces\Http\Requests\Auth\RegisterRequest;
use App\Interfaces\Http\Requests\Auth\LoginRequest;
use App\Interfaces\Http\Requests\Auth\ResetPasswordRequest;
use App\Helpers\ApiResponseHelper;
use App\Application\User\Services\AuthService;
use Illuminate\Http\Request;

class AuthController
{
    public function __construct(protected AuthService $authService) {}

    public function register(RegisterRequest $request)
    {
        try {
            $user = $this->authService->register($request->validated());
            return ApiResponseHelper::responseApi([
                'user' => $user,
            ], 'register_success', 201);
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $user = $this->authService->login($request->only('email', 'password'));
            if (!$user) {
                return ApiResponseHelper::responseApi([], 'login_failed', 401);
            }
            $token = $user->createToken('authToken')->accessToken;
            return ApiResponseHelper::responseApi([
                'user' => $user,
                'access_token' => $token,
            ], 'login_success');
        } catch (\Throwable $e) {
            dd($e);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $success = $this->authService->resetPassword($request->only('email', 'password'));
            if (!$success) {
                return ApiResponseHelper::responseApi([], 'reset_failed', 400);
            }
            return ApiResponseHelper::responseApi([], 'reset_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            if ($user && $user->token()) {
                $user->token()->revoke();
            }
            return ApiResponseHelper::responseApi([], 'logout_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }
}
