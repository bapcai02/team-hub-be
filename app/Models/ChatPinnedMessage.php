<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatPinnedMessage extends Model
{
    protected $table = 'chat_pinned_messages';
    
    protected $fillable = [
        'conversation_id',
        'message_id',
        'pinned_by',
    ];

    /**
     * Get the conversation that owns the pinned message
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    /**
     * Get the message that was pinned
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'message_id');
    }

    /**
     * Get the user who pinned the message
     */
    public function pinnedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pinned_by');
    }
} 