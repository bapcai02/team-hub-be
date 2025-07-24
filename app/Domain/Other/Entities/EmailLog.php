<?php

namespace App\Domain\Other\Entities;

class EmailLog
{
    public function __construct(
        public int $id,
        public string $to,
        public string $subject,
        public string $body,
        public ?string $status = null,
        public ?string $sent_at = null,
        public ?string $error_message = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->to,
            $model->subject,
            $model->body,
            $model->sent_at,
            $model->status,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 