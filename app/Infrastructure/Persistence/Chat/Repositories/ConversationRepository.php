<?php

namespace App\Infrastructure\Persistence\Chat\Repositories;

use App\Domain\Chat\Repositories\ConversationRepositoryInterface;
use App\Domain\Chat\Entities\Conversation;
use App\Models\Conversation as ConversationModel;
use App\Models\ConversationParticipant;

class ConversationRepository implements ConversationRepositoryInterface
{
    public function create(array $data): Conversation
    {
        $model = ConversationModel::create($data);
        return Conversation::fromModel($model);
    }

    public function find($id): ?Conversation
    {
        $model = ConversationModel::find($id);
        return $model ? Conversation::fromModel($model) : null;
    }

    public function findPersonalBetween($userId1, $userId2): ?Conversation
    {
        $conversation = ConversationModel::where('type', 'personal')
            ->whereHas('participants', function($q) use ($userId1) {
                $q->where('user_id', $userId1);
            })
            ->whereHas('participants', function($q) use ($userId2) {
                $q->where('user_id', $userId2);
            })
            ->first();
        return $conversation ? Conversation::fromModel($conversation) : null;
    }

    public function getByUserId($userId): array
    {
        $conversationIds = ConversationParticipant::where('user_id', $userId)->pluck('conversation_id');
        $conversations = ConversationModel::whereIn('id', $conversationIds)->get();
        return $conversations->map(fn($model) => Conversation::fromModel($model))->all();
    }

    public function update($id, array $data): ?Conversation
    {
        $model = ConversationModel::find($id);
        if (!$model) return null;
        $model->update($data);
        return Conversation::fromModel($model);
    }

    public function delete($id): bool
    {
        $model = ConversationModel::find($id);
        if (!$model) return false;
        $model->delete();
        return true;
    }
} 