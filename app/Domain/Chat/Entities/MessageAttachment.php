<?php

namespace App\Domain\Chat\Entities;

class MessageAttachment
{
    public function __construct(
        public int $id,
        public int $message_id,
        public string $file_path,
        public string $file_type,
        public int $uploaded_by,
        public string $uploaded_at,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->message_id,
            $model->file_path,
            $model->file_type,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 