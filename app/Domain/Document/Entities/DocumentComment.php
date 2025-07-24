<?php

namespace App\Domain\Document\Entities;

class DocumentComment
{
    public function __construct(
        public int $id,
        public int $document_id,
        public ?int $block_id = null,
        public int $user_id,
        public string $comment,
        public string $created_at,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->document_id,
            $model->user_id,
            $model->content,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 