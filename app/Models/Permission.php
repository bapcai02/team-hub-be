<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $fillable = ['name', 'description', 'module', 'is_active'];
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get roles that have this permission
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'permission_role');
    }

    /**
     * Get users that have this permission through their roles
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user', 'permission_id', 'user_id')
            ->using(Role::class);
    }

    /**
     * Scope to get permissions by module
     */
    public function scopeByModule($query, string $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope to get active permissions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
} 