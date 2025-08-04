<?php

namespace App\Interfaces\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Application\Project\Services\TaskCommentService;
use App\Interfaces\Http\Requests\Project\StoreTaskCommentRequest;
use App\Interfaces\Http\Requests\Project\UpdateTaskCommentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskCommentController
{
    public function __construct(protected TaskCommentService $taskCommentService) {}

    /**
     * Get all comments for a task.
     */
    public function index($taskId)
    {
        try {
            $comments = $this->taskCommentService->getByTaskId($taskId);
            return ApiResponseHelper::responseApi(['comments' => $comments], 'task_comments_success');
        } catch (\Throwable $e) {
            Log::error('TaskCommentController::index - Error getting task comments', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Create a new comment for a task.
     */
    public function store(StoreTaskCommentRequest $request, $taskId)
    {
        try {
            $data = $request->validated();
            $data['task_id'] = $taskId;
            $data['user_id'] = $request->user()->id;
            
            $comment = $this->taskCommentService->create($data);
            return ApiResponseHelper::responseApi(['comment' => $comment], 'task_comment_create_success', 201);
        } catch (\Throwable $e) {
            Log::error('TaskCommentController::store - Error creating task comment', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Update a comment.
     */
    public function update(UpdateTaskCommentRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $comment = $this->taskCommentService->update($id, $data);
            
            if (!$comment) {
                return ApiResponseHelper::responseApi([], 'comment_not_found', 404);
            }
            return ApiResponseHelper::responseApi(['comment' => $comment], 'task_comment_update_success');
        } catch (\Throwable $e) {
            Log::error('TaskCommentController::update - Error updating task comment', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Delete a comment.
     */
    public function destroy($id)
    {
        try {
            $success = $this->taskCommentService->delete($id);
            if (!$success) {
                return ApiResponseHelper::responseApi([], 'comment_not_found', 404);
            }
            return ApiResponseHelper::responseApi([], 'task_comment_delete_success');
        } catch (\Throwable $e) {
            Log::error('TaskCommentController::destroy - Error deleting task comment', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }
}