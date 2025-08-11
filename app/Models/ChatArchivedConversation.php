<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatArchivedConversation extends Model
{
    protected $table = 'chat_archived_conversations';
    public $timestamps = false;
    
    protected $fillable = [
        'conversation_id',
        'user_id',
        'archived_at',
        'restored_at',
    ];

    protected $casts = [
        'archived_at' => 'datetime',
        'restored_at' => 'datetime',
    ];

    /**
     * Get the conversation that was archived
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    /**
     * Get the user who archived the conversation
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Check if conversation is archived
     */
    public function isArchived(): bool
    {
        return $this->archived_at && !$this->restored_at;
    }

    /**
     * Restore archived conversation
     */
    public function restore(): void
    {
        $this->update(['restored_at' => now()]);
    }
} 