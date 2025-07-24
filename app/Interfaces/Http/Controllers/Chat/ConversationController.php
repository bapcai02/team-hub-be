<?php

namespace App\Interfaces\Http\Controllers\Chat;

use Illuminate\Http\Request;
use App\Application\Chat\Services\ConversationService;
use App\Helpers\ApiResponseHelper;
use App\Interfaces\Http\Requests\Chat\StoreConversationRequest;
use App\Interfaces\Http\Requests\Chat\UpdateConversationRequest;

class ConversationController
{
    public function __construct(protected ConversationService $conversationService) {}

    public function index(Request $request)
    {
        try {
            $userId = $request->user()->id;
            $conversations = $this->conversationService->getByUserId($userId);
            return ApiResponseHelper::responseApi(['conversations' => $conversations], 'conversation_list_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    public function store(StoreConversationRequest $request)
    {
        try {
            $userId = $request->user()->id;
            $data = $request->validated();
            $memberIds = $data['member_ids'];
            if ($data['type'] === 'personal') {
                if (count($memberIds) !== 1) {
                    return ApiResponseHelper::responseApi([], 'personal_must_have_1_member', 400);
                }
                $otherUserId = $memberIds[0];
                $exists = $this->conversationService->findPersonalBetween($userId, $otherUserId);
                if ($exists) {
                    return ApiResponseHelper::responseApi(['conversation' => $exists], 'personal_conversation_exists', 200);
                }
                $data['name'] = null;
            }
            $data['created_by'] = $userId;
            $conversation = $this->conversationService->create($data);
            return ApiResponseHelper::responseApi(['conversation' => $conversation], 'conversation_create_success', 201);
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    public function show($id, Request $request)
    {
        try {
            $userId = $request->user()->id;
            $conversation = $this->conversationService->find($id);
            if (!$conversation) {
                return ApiResponseHelper::responseApi([], 'conversation_not_found', 404);
            }
            // TODO: kiểm tra user có phải thành viên không
            return ApiResponseHelper::responseApi(['conversation' => $conversation], 'conversation_get_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    public function update(UpdateConversationRequest $request, $id)
    {
        try {
            $userId = $request->user()->id;
            $data = $request->validated();
            // TODO: kiểm tra quyền, xử lý thêm/xóa thành viên
            $conversation = $this->conversationService->update($id, $data);
            return ApiResponseHelper::responseApi(['conversation' => $conversation], 'conversation_update_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    public function destroy($id, Request $request)
    {
        try {
            $userId = $request->user()->id;
            // TODO: kiểm tra quyền, nếu là personal thì chỉ rời phòng, nếu là group thì xóa nếu là chủ phòng
            $success = $this->conversationService->delete($id);
            if (!$success) {
                return ApiResponseHelper::responseApi([], 'conversation_not_found', 404);
            }
            return ApiResponseHelper::responseApi([], 'conversation_delete_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }
} 