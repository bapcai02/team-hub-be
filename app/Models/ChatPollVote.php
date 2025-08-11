<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatPollVote extends Model
{
    protected $table = 'chat_poll_votes';
    
    protected $fillable = [
        'poll_id',
        'option_id',
        'user_id',
    ];

    /**
     * Get the poll that owns the vote
     */
    public function poll(): BelongsTo
    {
        return $this->belongsTo(ChatPoll::class, 'poll_id');
    }

    /**
     * Get the option that was voted for
     */
    public function option(): BelongsTo
    {
        return $this->belongsTo(ChatPollOption::class, 'option_id');
    }

    /**
     * Get the user who voted
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
} 