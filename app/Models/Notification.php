<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'title',
        'message',
        'data',
        'status',
        'priority',
        'scheduled_at',
        'sent_at',
        'retry_count',
        'error_message',
        'recipients',
        'channel',
        'is_read',
        'category',
        'action_url',
        'metadata',
    ];

    protected $casts = [
        'data' => 'array',
        'recipients' => 'array',
        'metadata' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'is_read' => 'boolean',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_SENT = 'sent';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    // Priority constants
    const PRIORITY_LOW = 'low';
    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    // Type constants
    const TYPE_EMAIL = 'email';
    const TYPE_PUSH = 'push';
    const TYPE_SMS = 'sms';
    const TYPE_IN_APP = 'in_app';

    // Category constants
    const CATEGORY_SYSTEM = 'system';
    const CATEGORY_PROJECT = 'project';
    const CATEGORY_FINANCE = 'finance';
    const CATEGORY_HR = 'hr';
    const CATEGORY_DEVICE = 'device';
    const CATEGORY_CONTRACT = 'contract';

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeScheduled($query)
    {
        return $query->where('scheduled_at', '<=', now());
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function markAsSent()
    {
        $this->update([
            'status' => self::STATUS_SENT,
            'sent_at' => now(),
        ]);
    }

    public function markAsFailed($errorMessage = null)
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'error_message' => $errorMessage,
            'retry_count' => $this->retry_count + 1,
        ]);
    }

    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    public function isUrgent()
    {
        return $this->priority === self::PRIORITY_URGENT;
    }

    public function isHighPriority()
    {
        return in_array($this->priority, [self::PRIORITY_HIGH, self::PRIORITY_URGENT]);
    }

    public function canRetry()
    {
        return $this->status === self::STATUS_FAILED && $this->retry_count < 3;
    }
} 