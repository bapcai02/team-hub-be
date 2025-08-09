<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category',
        'channels',
        'frequency',
        'quiet_hours',
        'is_active',
        'custom_settings',
    ];

    protected $casts = [
        'channels' => 'array',
        'frequency' => 'array',
        'quiet_hours' => 'array',
        'custom_settings' => 'array',
        'is_active' => 'boolean',
    ];

    // Category constants
    const CATEGORY_SYSTEM = 'system';
    const CATEGORY_PROJECT = 'project';
    const CATEGORY_FINANCE = 'finance';
    const CATEGORY_HR = 'hr';
    const CATEGORY_DEVICE = 'device';
    const CATEGORY_CONTRACT = 'contract';

    // Channel constants
    const CHANNEL_EMAIL = 'email';
    const CHANNEL_PUSH = 'push';
    const CHANNEL_SMS = 'sms';
    const CHANNEL_IN_APP = 'in_app';

    // Frequency constants
    const FREQUENCY_IMMEDIATE = 'immediate';
    const FREQUENCY_DAILY = 'daily';
    const FREQUENCY_WEEKLY = 'weekly';
    const FREQUENCY_NEVER = 'never';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function isChannelEnabled($channel)
    {
        return in_array($channel, $this->channels ?? []);
    }

    public function isInQuietHours()
    {
        if (!$this->quiet_hours) {
            return false;
        }

        $now = now();
        $start = $this->quiet_hours['start'] ?? '22:00';
        $end = $this->quiet_hours['end'] ?? '08:00';

        $currentTime = $now->format('H:i');
        
        if ($start <= $end) {
            return $currentTime >= $start && $currentTime <= $end;
        } else {
            // Handle overnight quiet hours
            return $currentTime >= $start || $currentTime <= $end;
        }
    }

    public function shouldSendImmediately()
    {
        return ($this->frequency['type'] ?? self::FREQUENCY_IMMEDIATE) === self::FREQUENCY_IMMEDIATE;
    }

    public function getNextSendTime()
    {
        $frequency = $this->frequency['type'] ?? self::FREQUENCY_IMMEDIATE;
        
        switch ($frequency) {
            case self::FREQUENCY_DAILY:
                return now()->addDay();
            case self::FREQUENCY_WEEKLY:
                return now()->addWeek();
            default:
                return now();
        }
    }
}
