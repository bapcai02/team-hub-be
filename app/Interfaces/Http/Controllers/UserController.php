<?php

namespace App\Interfaces\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Application\User\Services\UserService;
use App\Interfaces\Http\Requests\User\StoreUserRequest;
use App\Interfaces\Http\Requests\User\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController
{
    public function __construct(protected UserService $userService) {}

    /**
     * Get all users.
     */
    public function index()
    {
        try {
            $users = $this->userService->getAll();
            return ApiResponseHelper::responseApi(['users' => $users], 'user_list_success');
        } catch (\Throwable $e) {
            Log::error('UserController::index - Error getting all users', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Create a new user.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $data = $request->validated();
            $user = $this->userService->create($data);
            return ApiResponseHelper::responseApi(['user' => $user], 'user_create_success', 201);
        } catch (\Throwable $e) {
            Log::error('UserController::store - Error creating user', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get user details by ID.
     */
    public function show($id)
    {
        try {
            $user = $this->userService->getById($id);
            if (!$user) {
                return ApiResponseHelper::responseApi([], 'user_not_found', 404);
            }
            return ApiResponseHelper::responseApi(['user' => $user], 'user_get_success');
        } catch (\Throwable $e) {
            Log::error('UserController::show - Error getting user details', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Update user details.
     */
    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $user = $this->userService->update($id, $data);
            
            if (!$user) {
                return ApiResponseHelper::responseApi([], 'user_not_found', 404);
            }
            return ApiResponseHelper::responseApi(['user' => $user], 'user_update_success');
        } catch (\Throwable $e) {
            Log::error('UserController::update - Error updating user', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Delete user.
     */
    public function destroy($id)
    {
        try {
            $success = $this->userService->delete($id);
            if (!$success) {
                return ApiResponseHelper::responseApi([], 'user_not_found', 404);
            }
            return ApiResponseHelper::responseApi([], 'user_delete_success');
        } catch (\Throwable $e) {
            Log::error('UserController::destroy - Error deleting user', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get users by status.
     */
    public function getByStatus(Request $request)
    {
        try {
            $status = $request->query('status');
            if (!$status) {
                return ApiResponseHelper::responseApi([], 'status_required', 400);
            }
            
            $users = $this->userService->getByStatus($status);
            return ApiResponseHelper::responseApi(['users' => $users], 'user_status_success');
        } catch (\Throwable $e) {
            Log::error('UserController::getByStatus - Error getting users by status', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get users by role.
     */
    public function getByRole(Request $request)
    {
        try {
            $roleId = $request->query('role_id');
            if (!$roleId) {
                return ApiResponseHelper::responseApi([], 'role_id_required', 400);
            }
            
            $users = $this->userService->getByRole($roleId);
            return ApiResponseHelper::responseApi(['users' => $users], 'user_role_success');
        } catch (\Throwable $e) {
            Log::error('UserController::getByRole - Error getting users by role', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get active users.
     */
    public function getActiveUsers()
    {
        try {
            $users = $this->userService->getActiveUsers();
            return ApiResponseHelper::responseApi(['users' => $users], 'user_active_success');
        } catch (\Throwable $e) {
            Log::error('UserController::getActiveUsers - Error getting active users', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }
}
