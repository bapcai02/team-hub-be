<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentShare extends Model
{
    use SoftDeletes;
    
    protected $table = 'document_shares';
    
    protected $fillable = [
        'document_id',
        'user_id',
        'permission',
        'shared_by',
        'shared_at',
        'expires_at',
    ];

    protected $casts = [
        'shared_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public $timestamps = false; // Using custom shared_at field

    // Relationships
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sharedBy()
    {
        return $this->belongsTo(User::class, 'shared_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function scopeByPermission($query, $permission)
    {
        return $query->where('permission', $permission);
    }

    // Helper methods
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function canView()
    {
        return in_array($this->permission, ['view', 'edit', 'comment']) && !$this->isExpired();
    }

    public function canEdit()
    {
        return $this->permission === 'edit' && !$this->isExpired();
    }

    public function canComment()
    {
        return in_array($this->permission, ['edit', 'comment']) && !$this->isExpired();
    }
}