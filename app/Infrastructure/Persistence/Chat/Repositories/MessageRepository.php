<?php

namespace App\Infrastructure\Persistence\Chat\Repositories;

use App\Domain\Chat\Repositories\MessageRepositoryInterface;
use App\Domain\Chat\Entities\Message;
use App\Models\Message as MessageModel;

class MessageRepository implements MessageRepositoryInterface
{
    public function create(array $data): Message
    {
        $model = MessageModel::create($data);
        return Message::fromModel($model);
    }

    public function find($id): ?Message
    {
        $model = MessageModel::find($id);
        return $model ? Message::fromModel($model) : null;
    }

    public function getByConversationId($conversationId, $before = null, $limit = 50): array
    {
        $query = MessageModel::where('conversation_id', $conversationId)
            ->orderByDesc('id');
        if ($before) {
            $query->where('created_at', '<', $before);
        }
        $messages = $query->limit($limit)->get();
        return $messages->map(fn($model) => Message::fromModel($model))->all();
    }

    public function update($id, array $data): ?Message
    {
        $model = MessageModel::find($id);
        if (!$model) return null;
        $model->update($data);
        return Message::fromModel($model);
    }

    public function delete($id): bool
    {
        $model = MessageModel::find($id);
        if (!$model) return false;
        $model->delete();
        return true;
    }
} 