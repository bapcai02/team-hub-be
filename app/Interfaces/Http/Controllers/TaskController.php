<?php

namespace App\Interfaces\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Application\Project\Services\TaskService;
use App\Interfaces\Http\Requests\Project\StoreTaskRequest;
use App\Interfaces\Http\Requests\Project\UpdateTaskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController
{
    public function __construct(protected TaskService $taskService) {}

    /**
     * Create a new task.
     */
    public function store(StoreTaskRequest $request)
    {
        try {
            $data = $request->validated();
            $data['created_by'] = $request->user()->id;
            
            $task = $this->taskService->create($data);
            return ApiResponseHelper::responseApi(['task' => $task], 'task_create_success', 201);
        } catch (\Throwable $e) {
            Log::error('TaskController::store - Error creating task', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get all tasks for a project.
     */
    public function index(Request $request, $projectId)
    {
        try {
            // Debug: Log the projectId
            Log::info('TaskController::index - Project ID:', ['projectId' => $projectId]);
            
            $tasks = $this->taskService->getByProjectId($projectId);
            return ApiResponseHelper::responseApi(['tasks' => $tasks], 'task_list_success');
        } catch (\Throwable $e) {
            Log::error('TaskController::index - Error getting tasks for project', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get task details by ID.
     */
    public function show($id)
    {
        try {
            $task = $this->taskService->find($id);
            if (!$task) {
                return ApiResponseHelper::responseApi([], 'task_not_found', 404);
            }
            return ApiResponseHelper::responseApi(['task' => $task], 'task_get_success');
        } catch (\Throwable $e) {
            Log::error('TaskController::show - Error getting task details', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Update task details.
     */
    public function update(UpdateTaskRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $task = $this->taskService->update($id, $data);
            
            if (!$task) {
                return ApiResponseHelper::responseApi([], 'task_not_found', 404);
            }
            return ApiResponseHelper::responseApi(['task' => $task], 'task_update_success');
        } catch (\Throwable $e) {
            Log::error('TaskController::update - Error updating task', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Delete task.
     */
    public function destroy($id)
    {
        try {
            $success = $this->taskService->delete($id);
            if (!$success) {
                return ApiResponseHelper::responseApi([], 'task_not_found', 404);
            }
            return ApiResponseHelper::responseApi([], 'task_delete_success');
        } catch (\Throwable $e) {
            Log::error('TaskController::destroy - Error deleting task', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get tasks by assignee.
     */
    public function getByAssignee(Request $request)
    {
        try {
            $userId = $request->user()->id;
            $tasks = $this->taskService->getByAssignee($userId);
            return ApiResponseHelper::responseApi(['tasks' => $tasks], 'task_assignee_success');
        } catch (\Throwable $e) {
            Log::error('TaskController::getByAssignee - Error getting tasks by assignee', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get tasks by status in a project.
     */
    public function getByStatus(Request $request, $projectId)
    {
        try {
            $status = $request->query('status');
            if (!$status) {
                return ApiResponseHelper::responseApi([], 'status_required', 400);
            }
            
            $tasks = $this->taskService->getByStatus($projectId, $status);
            return ApiResponseHelper::responseApi(['tasks' => $tasks], 'task_status_success');
        } catch (\Throwable $e) {
            Log::error('TaskController::getByStatus - Error getting tasks by status', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }
} 