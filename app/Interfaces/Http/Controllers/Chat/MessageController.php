<?php

namespace App\Interfaces\Http\Controllers\Chat;

use Illuminate\Http\Request;
use App\Application\Chat\Services\MessageService;
use App\Helpers\ApiResponseHelper;
use App\Interfaces\Http\Requests\Chat\StoreMessageRequest;
use App\Interfaces\Http\Requests\Chat\UpdateMessageRequest;

class MessageController
{
    public function __construct(protected MessageService $messageService) {}

    public function index(Request $request, $conversationId)
    {
        try {
            $before = $request->query('before');
            $limit = $request->query('limit', 50);
            $messages = $this->messageService->getByConversationId($conversationId, $before, $limit);
            return ApiResponseHelper::responseApi(['messages' => $messages], 'message_list_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    public function store(StoreMessageRequest $request, $conversationId)
    {
        try {
            $userId = $request->user()->id;
            $data = $request->validated();
            $data['conversation_id'] = $conversationId;
            $data['sender_id'] = $userId;
            $message = $this->messageService->create($data);
            return ApiResponseHelper::responseApi(['message' => $message], 'message_create_success', 201);
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    public function update(UpdateMessageRequest $request, $id)
    {
        try {
            $userId = $request->user()->id;
            $data = $request->validated();
            $message = $this->messageService->update($id, $data);
            return ApiResponseHelper::responseApi(['message' => $message], 'message_update_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    public function destroy($id, Request $request)
    {
        try {
            $userId = $request->user()->id;
            $success = $this->messageService->delete($id);
            if (!$success) {
                return ApiResponseHelper::responseApi([], 'message_not_found', 404);
            }
            return ApiResponseHelper::responseApi([], 'message_delete_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }
} 