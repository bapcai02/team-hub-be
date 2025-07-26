<?php

namespace App\Interfaces\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Application\Project\Services\TaskTagService;
use App\Interfaces\Http\Requests\Project\StoreTaskTagRequest;
use App\Interfaces\Http\Requests\Project\UpdateTaskTagRequest;
use App\Interfaces\Http\Requests\Project\AssignTaskTagRequest;
use Illuminate\Http\Request;

class TaskTagController
{
    public function __construct(protected TaskTagService $taskTagService) {}

    /**
     * Get all task tags.
     */
    public function index()
    {
        try {
            $tags = $this->taskTagService->getAll();
            return ApiResponseHelper::responseApi(['tags' => $tags], 'task_tags_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Create a new task tag.
     */
    public function store(StoreTaskTagRequest $request)
    {
        try {
            $data = $request->validated();
            $tag = $this->taskTagService->create($data);
            return ApiResponseHelper::responseApi(['tag' => $tag], 'task_tag_create_success', 201);
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get task tag details.
     */
    public function show($id)
    {
        try {
            $tag = $this->taskTagService->find($id);
            if (!$tag) {
                return ApiResponseHelper::responseApi([], 'tag_not_found', 404);
            }
            return ApiResponseHelper::responseApi(['tag' => $tag], 'task_tag_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Update task tag.
     */
    public function update(UpdateTaskTagRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $tag = $this->taskTagService->update($id, $data);
            
            if (!$tag) {
                return ApiResponseHelper::responseApi([], 'tag_not_found', 404);
            }
            return ApiResponseHelper::responseApi(['tag' => $tag], 'task_tag_update_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Delete task tag.
     */
    public function destroy($id)
    {
        try {
            $success = $this->taskTagService->delete($id);
            if (!$success) {
                return ApiResponseHelper::responseApi([], 'tag_not_found', 404);
            }
            return ApiResponseHelper::responseApi([], 'task_tag_delete_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get tags for a specific task.
     */
    public function getTaskTags($taskId)
    {
        try {
            $tags = $this->taskTagService->getByTaskId($taskId);
            return ApiResponseHelper::responseApi(['tags' => $tags], 'task_tags_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Assign tags to a task.
     */
    public function assignToTask(AssignTaskTagRequest $request, $taskId)
    {
        try {
            $data = $request->validated();
            $this->taskTagService->assignToTask($taskId, $data['tag_ids']);
            return ApiResponseHelper::responseApi([], 'task_tags_assigned_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Remove tags from a task.
     */
    public function removeFromTask(Request $request, $taskId)
    {
        try {
            $tagIds = $request->input('tag_ids', []);
            $this->taskTagService->removeFromTask($taskId, $tagIds);
            return ApiResponseHelper::responseApi([], 'task_tags_removed_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }
}