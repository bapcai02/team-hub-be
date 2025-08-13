<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSetting extends Model
{
    protected $table = 'user_settings';
    
    protected $fillable = [
        'user_id',
        'language',
        'timezone',
        'notification_preferences',
        'theme',
        // Profile settings
        'avatar',
        'phone',
        'bio',
        'location',
        // App settings
        'layout',
        'sidebar_collapsed',
        'dashboard_widgets',
        'shortcuts',
        // Security settings
        'two_factor_enabled',
        'login_history',
        'trusted_devices',
        // Integration settings
        'integrations',
        'calendar_sync',
        'email_signature',
        // Advanced settings
        'privacy_settings',
        'accessibility_settings',
        'data_export_preferences',
    ];

    protected $casts = [
        'notification_preferences' => 'array',
        'dashboard_widgets' => 'array',
        'shortcuts' => 'array',
        'login_history' => 'array',
        'trusted_devices' => 'array',
        'integrations' => 'array',
        'privacy_settings' => 'array',
        'accessibility_settings' => 'array',
        'data_export_preferences' => 'array',
        'sidebar_collapsed' => 'boolean',
        'two_factor_enabled' => 'boolean',
    ];

    /**
     * Get the user that owns the settings
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get notification preferences for a specific category
     */
    public function getNotificationPreference(string $category, string $key = null)
    {
        $preferences = $this->notification_preferences ?? [];
        
        if ($key) {
            return $preferences[$category][$key] ?? null;
        }
        
        return $preferences[$category] ?? null;
    }

    /**
     * Set notification preference for a specific category
     */
    public function setNotificationPreference(string $category, array $settings): void
    {
        $preferences = $this->notification_preferences ?? [];
        $preferences[$category] = $settings;
        $this->notification_preferences = $preferences;
    }

    /**
     * Get privacy setting
     */
    public function getPrivacySetting(string $key)
    {
        $settings = $this->privacy_settings ?? [];
        return $settings[$key] ?? null;
    }

    /**
     * Set privacy setting
     */
    public function setPrivacySetting(string $key, $value): void
    {
        $settings = $this->privacy_settings ?? [];
        $settings[$key] = $value;
        $this->privacy_settings = $settings;
    }

    /**
     * Get accessibility setting
     */
    public function getAccessibilitySetting(string $key)
    {
        $settings = $this->accessibility_settings ?? [];
        return $settings[$key] ?? null;
    }

    /**
     * Set accessibility setting
     */
    public function setAccessibilitySetting(string $key, $value): void
    {
        $settings = $this->accessibility_settings ?? [];
        $settings[$key] = $value;
        $this->accessibility_settings = $settings;
    }
} 