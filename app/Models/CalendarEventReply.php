<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CalendarEventReply extends Model
{
    use SoftDeletes;

    protected $table = 'calendar_event_replies';

    protected $fillable = [
        'event_id',
        'user_id',
        'content',
        'parent_reply_id',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the event this reply belongs to
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(CalendarEvent::class, 'event_id');
    }

    /**
     * Get the user who created this reply
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the parent reply (for nested replies)
     */
    public function parentReply(): BelongsTo
    {
        return $this->belongsTo(CalendarEventReply::class, 'parent_reply_id');
    }

    /**
     * Get child replies (nested replies)
     */
    public function replies()
    {
        return $this->hasMany(CalendarEventReply::class, 'parent_reply_id');
    }

    /**
     * Scope for root replies (not nested)
     */
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_reply_id');
    }

    /**
     * Scope for replies by event
     */
    public function scopeByEvent($query, int $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    /**
     * Scope for replies by user
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
} 