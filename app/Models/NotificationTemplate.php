<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationTemplate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'category',
        'type',
        'title_template',
        'message_template',
        'variables',
        'channels',
        'priority',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'variables' => 'array',
        'channels' => 'array',
        'metadata' => 'array',
        'is_active' => 'boolean',
    ];

    // Category constants
    const CATEGORY_SYSTEM = 'system';
    const CATEGORY_PROJECT = 'project';
    const CATEGORY_FINANCE = 'finance';
    const CATEGORY_HR = 'hr';
    const CATEGORY_DEVICE = 'device';
    const CATEGORY_CONTRACT = 'contract';

    // Type constants
    const TYPE_EMAIL = 'email';
    const TYPE_PUSH = 'push';
    const TYPE_SMS = 'sms';
    const TYPE_IN_APP = 'in_app';

    // Priority constants
    const PRIORITY_LOW = 'low';
    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function renderTitle($data = [])
    {
        return $this->replaceVariables($this->title_template, $data);
    }

    public function renderMessage($data = [])
    {
        return $this->replaceVariables($this->message_template, $data);
    }

    private function replaceVariables($template, $data)
    {
        $variables = $this->variables ?? [];
        
        foreach ($variables as $variable) {
            $key = $variable['key'] ?? '';
            $value = $data[$key] ?? $variable['default'] ?? '';
            $template = str_replace("{{$key}}", $value, $template);
        }

        return $template;
    }

    public function isChannelEnabled($channel)
    {
        return in_array($channel, $this->channels ?? []);
    }

    public function getAvailableVariables()
    {
        return $this->variables ?? [];
    }

    public function validateData($data)
    {
        $requiredVariables = collect($this->variables ?? [])
            ->where('required', true)
            ->pluck('key')
            ->toArray();

        $missingVariables = array_diff($requiredVariables, array_keys($data));

        if (!empty($missingVariables)) {
            throw new \Exception("Missing required variables: " . implode(', ', $missingVariables));
        }

        return true;
    }
}
