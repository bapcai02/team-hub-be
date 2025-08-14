<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CalendarEvent extends Model
{
    use SoftDeletes;

    protected $table = 'calendar_events';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start_time',
        'end_time',
        'event_type',
        'color',
        'is_all_day',
        'location',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_all_day' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    protected $appends = [
        'reply_count',
    ];

    /**
     * Get the user who created this event
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get participants for this event
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'calendar_event_participants', 'event_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Get replies for this event
     */
    public function replies()
    {
        return $this->hasMany(CalendarEventReply::class, 'event_id');
    }

    /**
     * Get root replies (not nested)
     */
    public function rootReplies()
    {
        return $this->hasMany(CalendarEventReply::class, 'event_id')
            ->whereNull('parent_reply_id');
    }

    /**
     * Get reply count attribute
     */
    public function getReplyCountAttribute(): int
    {
        return $this->replies()->count();
    }

    /**
     * Scope for upcoming events
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>', now());
    }

    /**
     * Scope for past events
     */
    public function scopePast($query)
    {
        return $query->where('end_time', '<', now());
    }

    /**
     * Scope for ongoing events
     */
    public function scopeOngoing($query)
    {
        $now = now();
        return $query->where('start_time', '<=', $now)
                    ->where('end_time', '>=', $now);
    }

    /**
     * Scope for events by type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('event_type', $type);
    }

    /**
     * Scope for events by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for events by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('start_time', [$startDate, $endDate]);
    }

    /**
     * Scope for events by user
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
} 