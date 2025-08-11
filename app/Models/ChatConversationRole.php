<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatConversationRole extends Model
{
    protected $table = 'chat_conversation_roles';
    public $timestamps = false;
    
    protected $fillable = [
        'conversation_id',
        'user_id',
        'role',
        'joined_at',
        'is_online',
        'last_seen_at',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'is_online' => 'boolean',
        'last_seen_at' => 'datetime',
    ];

    /**
     * Get the conversation that owns the role
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    /**
     * Get the user who has the role
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is moderator
     */
    public function isModerator(): bool
    {
        return $this->role === 'moderator';
    }

    /**
     * Check if user is member
     */
    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    /**
     * Update online status
     */
    public function updateOnlineStatus(bool $isOnline): void
    {
        $this->update([
            'is_online' => $isOnline,
            'last_seen_at' => $isOnline ? null : now(),
        ]);
    }
} 