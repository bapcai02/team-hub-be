<?php

namespace App\Interfaces\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Application\User\Services\UserService;

class UserController
{
    public function __construct(protected UserService $userService) {}

    public function index()
    {
        try {
            $users = $this->userService->getAll();
            return ApiResponseHelper::responseApi(['users' => $users], 'user_list_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    public function show($id)
    {
        try {
            $user = $this->userService->getById($id);
            if (!$user) {
                return ApiResponseHelper::responseApi([], 'user_not_found', 404);
            }
            return ApiResponseHelper::responseApi(['user' => $user], 'user_get_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }
}
