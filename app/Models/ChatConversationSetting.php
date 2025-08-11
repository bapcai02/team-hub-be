<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatConversationSetting extends Model
{
    protected $table = 'chat_conversation_settings';
    
    protected $fillable = [
        'conversation_id',
        'name',
        'description',
        'is_private',
        'allow_invites',
        'read_only',
        'slow_mode',
        'slow_mode_interval',
        'theme',
    ];

    protected $casts = [
        'is_private' => 'boolean',
        'allow_invites' => 'boolean',
        'read_only' => 'boolean',
        'slow_mode' => 'boolean',
        'slow_mode_interval' => 'integer',
        'theme' => 'array',
    ];

    /**
     * Get the conversation that owns the settings
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    /**
     * Get default theme settings
     */
    public static function getDefaultTheme(): array
    {
        return [
            'primaryColor' => '#1890ff',
            'secondaryColor' => '#52c41a',
            'backgroundColor' => '#ffffff',
            'textColor' => '#000000',
            'messageBubbleColor' => '#f0f0f0',
            'messageBubbleTextColor' => '#000000',
            'sidebarColor' => '#f5f5f5',
            'borderRadius' => 8,
            'fontSize' => 14,
            'darkMode' => false,
        ];
    }

    /**
     * Apply theme settings
     */
    public function applyTheme(array $theme): void
    {
        $this->update(['theme' => array_merge(self::getDefaultTheme(), $theme)]);
    }
} 