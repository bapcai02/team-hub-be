<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatTypingStatus extends Model
{
    protected $table = 'chat_typing_status';
    
    protected $fillable = [
        'conversation_id',
        'user_id',
        'is_typing',
        'last_typing_at',
    ];

    protected $casts = [
        'is_typing' => 'boolean',
        'last_typing_at' => 'datetime',
    ];

    /**
     * Get the conversation that owns the typing status
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    /**
     * Get the user who is typing
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Start typing
     */
    public function startTyping(): void
    {
        $this->update([
            'is_typing' => true,
            'last_typing_at' => now(),
        ]);
    }

    /**
     * Stop typing
     */
    public function stopTyping(): void
    {
        $this->update(['is_typing' => false]);
    }

    /**
     * Check if user is still typing (within 10 seconds)
     */
    public function isStillTyping(): bool
    {
        return $this->is_typing && 
               $this->last_typing_at && 
               $this->last_typing_at->diffInSeconds(now()) < 10;
    }
} 