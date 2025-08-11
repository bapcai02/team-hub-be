<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatPoll extends Model
{
    use SoftDeletes;

    protected $table = 'chat_polls';
    
    protected $fillable = [
        'conversation_id',
        'question',
        'allow_multiple',
        'anonymous',
        'expires_at',
        'created_by',
    ];

    protected $casts = [
        'allow_multiple' => 'boolean',
        'anonymous' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the conversation that owns the poll
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    /**
     * Get the user who created the poll
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the poll options
     */
    public function options(): HasMany
    {
        return $this->hasMany(ChatPollOption::class, 'poll_id');
    }

    /**
     * Get the poll votes
     */
    public function votes(): HasMany
    {
        return $this->hasMany(ChatPollVote::class, 'poll_id');
    }

    /**
     * Check if poll is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Get total votes count
     */
    public function getTotalVotesAttribute(): int
    {
        return $this->options->sum('votes');
    }

    /**
     * Check if user has voted
     */
    public function hasUserVoted(int $userId): bool
    {
        return $this->votes()->where('user_id', $userId)->exists();
    }
} 