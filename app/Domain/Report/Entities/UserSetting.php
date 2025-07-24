<?php

namespace App\Domain\Report\Entities;

class UserSetting
{
    public function __construct(
        public int $id,
        public int $user_id,
        public string $language,
        public string $timezone,
        public ?string $notification_preferences = null,
        public string $theme = 'light',
        public ?string $created_at = null,
        public ?string $updated_at = null,
    ) {}

    public static function fromModel($model): self
    {
        return new self(
            $model->id,
            $model->user_id,
            $model->settings,
            $model->created_at?->toDateTimeString(),
            $model->updated_at?->toDateTimeString(),
        );
    }
} 