<?php

namespace App\Interfaces\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Application\Project\Services\KanbanService;
use App\Interfaces\Http\Requests\Project\StoreKanbanColumnRequest;
use App\Interfaces\Http\Requests\Project\UpdateKanbanColumnRequest;
use App\Interfaces\Http\Requests\Project\ReorderKanbanColumnsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KanbanController
{
    public function __construct(protected KanbanService $kanbanService) {}

    /**
     * Get kanban board for a project.
     */
    public function getBoard($projectId)
    {
        try {
            $columns = $this->kanbanService->getColumnsByProject($projectId);
            return ApiResponseHelper::responseApi(['columns' => $columns], 'kanban_board_success');
        } catch (\Throwable $e) {
            Log::error('KanbanController::getBoard - Error getting kanban board', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Create a new kanban column.
     */
    public function createColumn(StoreKanbanColumnRequest $request)
    {
        try {
            $data = $request->validated();
            $column = $this->kanbanService->createColumn($data);
            return ApiResponseHelper::responseApi(['column' => $column], 'kanban_column_create_success', 201);
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Update kanban column.
     */
    public function updateColumn(UpdateKanbanColumnRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $column = $this->kanbanService->updateColumn($id, $data);
            
            if (!$column) {
                return ApiResponseHelper::responseApi([], 'kanban_column_not_found', 404);
            }
            return ApiResponseHelper::responseApi(['column' => $column], 'kanban_column_update_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Delete kanban column.
     */
    public function deleteColumn($id)
    {
        try {
            $success = $this->kanbanService->deleteColumn($id);
            if (!$success) {
                return ApiResponseHelper::responseApi([], 'kanban_column_not_found', 404);
            }
            return ApiResponseHelper::responseApi([], 'kanban_column_delete_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Reorder kanban columns.
     */
    public function reorderColumns(ReorderKanbanColumnsRequest $request, $projectId)
    {
        try {
            $data = $request->validated();
            $success = $this->kanbanService->reorderColumns($projectId, $data['column_ids']);
            
            if (!$success) {
                return ApiResponseHelper::responseApi([], 'reorder_failed', 400);
            }
            return ApiResponseHelper::responseApi([], 'kanban_columns_reorder_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Initialize default kanban columns for a project.
     */
    public function initializeDefaultColumns($projectId)
    {
        try {
            $columns = $this->kanbanService->initializeDefaultColumns($projectId);
            return ApiResponseHelper::responseApi(['columns' => $columns], 'kanban_default_columns_success', 201);
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get project tasks for kanban board.
     */
    public function getProjectTasks($projectId)
    {
        try {
            if (!$projectId) {
                return ApiResponseHelper::responseApi([], 'project_id_required', 400);
            }
            
            $tasks = $this->kanbanService->getProjectTasks($projectId);
            return ApiResponseHelper::responseApi(['tasks' => $tasks], 'project_tasks_success');
        } catch (\Throwable $e) {
            Log::error('KanbanController::getProjectTasks - Error getting project tasks', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }
} 