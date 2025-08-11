<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatPollOption extends Model
{
    protected $table = 'chat_poll_options';
    
    protected $fillable = [
        'poll_id',
        'text',
        'votes',
    ];

    protected $casts = [
        'votes' => 'integer',
    ];

    /**
     * Get the poll that owns the option
     */
    public function poll(): BelongsTo
    {
        return $this->belongsTo(ChatPoll::class, 'poll_id');
    }

    /**
     * Get the votes for this option
     */
    public function optionVotes(): HasMany
    {
        return $this->hasMany(ChatPollVote::class, 'option_id');
    }

    /**
     * Get the percentage of votes for this option
     */
    public function getVotePercentageAttribute(): float
    {
        $totalVotes = $this->poll->total_votes;
        return $totalVotes > 0 ? round(($this->votes / $totalVotes) * 100, 2) : 0;
    }
} 