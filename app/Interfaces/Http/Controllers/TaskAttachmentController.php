<?php

namespace App\Interfaces\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Application\Project\Services\TaskAttachmentService;
use App\Interfaces\Http\Requests\Project\StoreTaskAttachmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TaskAttachmentController
{
    public function __construct(protected TaskAttachmentService $taskAttachmentService) {}

    /**
     * Get all attachments for a task.
     */
    public function index($taskId)
    {
        try {
            $attachments = $this->taskAttachmentService->getByTaskId($taskId);
            return ApiResponseHelper::responseApi(['attachments' => $attachments], 'task_attachments_success');
        } catch (\Throwable $e) {
            Log::error('TaskAttachmentController::index - Error getting task attachments', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Upload attachment for a task.
     */
    public function store(StoreTaskAttachmentRequest $request, $taskId)
    {
        try {
            $data = $request->validated();
            
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $file->store('task-attachments');
                
                $attachmentData = [
                    'task_id' => $taskId,
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'uploaded_by' => $request->user()->id,
                    'uploaded_at' => now(),
                ];
                
                $attachment = $this->taskAttachmentService->create($attachmentData);
                return ApiResponseHelper::responseApi(['attachment' => $attachment], 'task_attachment_upload_success', 201);
            }
            
            return ApiResponseHelper::responseApi([], 'file_required', 400);
        } catch (\Throwable $e) {
            Log::error('TaskAttachmentController::store - Error creating task attachment', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Download attachment.
     */
    public function download($id)
    {
        try {
            $attachment = $this->taskAttachmentService->find($id);
            if (!$attachment) {
                return ApiResponseHelper::responseApi([], 'attachment_not_found', 404);
            }
            
            if (!Storage::exists($attachment->file_path)) {
                return ApiResponseHelper::responseApi([], 'file_not_found', 404);
            }
            
            return Storage::download($attachment->file_path, $attachment->original_name ?? 'attachment');
        } catch (\Throwable $e) {
            Log::error('TaskAttachmentController::download - Error downloading task attachment', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Delete attachment.
     */
    public function destroy($id)
    {
        try {
            $attachment = $this->taskAttachmentService->find($id);
            if (!$attachment) {
                return ApiResponseHelper::responseApi([], 'attachment_not_found', 404);
            }
            
            // Delete file from storage
            if (Storage::exists($attachment->file_path)) {
                Storage::delete($attachment->file_path);
            }
            
            $success = $this->taskAttachmentService->delete($id);
            if (!$success) {
                return ApiResponseHelper::responseApi([], 'attachment_not_found', 404);
            }
            
            return ApiResponseHelper::responseApi([], 'task_attachment_delete_success');
        } catch (\Throwable $e) {
            Log::error('TaskAttachmentController::destroy - Error deleting task attachment', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }
}