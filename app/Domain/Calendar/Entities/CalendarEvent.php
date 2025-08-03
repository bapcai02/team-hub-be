<?php

namespace App\Domain\Calendar\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;
use App\Models\User;

class CalendarEvent extends Model
{
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
    ];

    protected $appends = [
        'duration_minutes',
        'is_upcoming',
        'is_today',
    ];

    /**
     * Get the user who created this event
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
     * Get duration in minutes
     */
    public function getDurationMinutesAttribute(): int
    {
        return $this->start_time->diffInMinutes($this->end_time);
    }

    /**
     * Check if event is upcoming
     */
    public function getIsUpcomingAttribute(): bool
    {
        return $this->start_time->isAfter(Carbon::now());
    }

    /**
     * Check if event is today
     */
    public function getIsTodayAttribute(): bool
    {
        return $this->start_time->isToday();
    }

    /**
     * Check if event is ongoing
     */
    public function isOngoing(): bool
    {
        $now = Carbon::now();
        return $now->between($this->start_time, $this->end_time);
    }

    /**
     * Check if event is completed
     */
    public function isCompleted(): bool
    {
        return $this->end_time->isPast();
    }

    /**
     * Get event status
     */
    public function getStatus(): string
    {
        if ($this->isOngoing()) {
            return 'ongoing';
        }
        
        if ($this->isCompleted()) {
            return 'completed';
        }
        
        return 'upcoming';
    }

    /**
     * Scope for upcoming events
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_time', '>', Carbon::now());
    }

    /**
     * Scope for today's events
     */
    public function scopeToday($query)
    {
        return $query->whereDate('start_time', Carbon::today());
    }

    /**
     * Scope for this month's events
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('start_time', Carbon::now()->month)
                    ->whereYear('start_time', Carbon::now()->year);
    }

    /**
     * Scope for events by type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('event_type', $type);
    }

    /**
     * Scope for events by date range
     */
    public function scopeByDateRange($query, Carbon $startDate, Carbon $endDate)
    {
        return $query->whereBetween('start_time', [$startDate, $endDate]);
    }
} 