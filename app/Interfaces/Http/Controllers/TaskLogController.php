<?php

namespace App\Interfaces\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Application\Project\Services\TaskLogService;
use App\Interfaces\Http\Requests\Project\StartTimeRequest;
use App\Interfaces\Http\Requests\Project\StopTimeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskLogController
{
    public function __construct(protected TaskLogService $taskLogService) {}

    /**
     * Start time tracking for a task.
     */
    public function startTime(StartTimeRequest $request)
    {
        try {
            $data = $request->validated();
            $userId = $request->user()->id;
            
            $taskLog = $this->taskLogService->startTime(
                $data['task_id'], 
                $userId, 
                $data['note'] ?? null
            );
            
            return ApiResponseHelper::responseApi(['task_log' => $taskLog], 'time_start_success');
        } catch (\Exception $e) {
            Log::error('TaskLogController::startTime - Error starting time tracking', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        } catch (\Throwable $e) {
            Log::error('TaskLogController::startTime - Error starting time tracking', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Stop time tracking for a task.
     */
    public function stopTime(StopTimeRequest $request)
    {
        try {
            $data = $request->validated();
            $userId = $request->user()->id;
            
            $taskLog = $this->taskLogService->stopTime($data['task_id'], $userId);
            
            return ApiResponseHelper::responseApi(['task_log' => $taskLog], 'time_stop_success');
        } catch (\Exception $e) {
            return ApiResponseHelper::responseApi([], $e->getMessage(), 400);
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get time logs for a task.
     */
    public function getByTask($taskId)
    {
        try {
            $logs = $this->taskLogService->getByTaskId($taskId);
            return ApiResponseHelper::responseApi(['logs' => $logs], 'task_logs_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get time logs for current user.
     */
    public function getMyLogs(Request $request)
    {
        try {
            $userId = $request->user()->id;
            $logs = $this->taskLogService->getByUserId($userId);
            return ApiResponseHelper::responseApi(['logs' => $logs], 'my_logs_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get active time log for a task.
     */
    public function getActiveLog(Request $request, $taskId)
    {
        try {
            $userId = $request->user()->id;
            $activeLog = $this->taskLogService->getActiveLog($taskId, $userId);
            
            return ApiResponseHelper::responseApi(['active_log' => $activeLog], 'active_log_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get total time spent on a task.
     */
    public function getTotalTimeByTask($taskId)
    {
        try {
            $totalMinutes = $this->taskLogService->getTotalTimeByTask($taskId);
            $hours = floor($totalMinutes / 60);
            $minutes = $totalMinutes % 60;
            
            return ApiResponseHelper::responseApi([
                'total_minutes' => $totalMinutes,
                'formatted_time' => sprintf('%02d:%02d', $hours, $minutes)
            ], 'total_time_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get total time spent by current user.
     */
    public function getMyTotalTime(Request $request)
    {
        try {
            $userId = $request->user()->id;
            $totalMinutes = $this->taskLogService->getTotalTimeByUser($userId);
            $hours = floor($totalMinutes / 60);
            $minutes = $totalMinutes % 60;
            
            return ApiResponseHelper::responseApi([
                'total_minutes' => $totalMinutes,
                'formatted_time' => sprintf('%02d:%02d', $hours, $minutes)
            ], 'my_total_time_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }
} 