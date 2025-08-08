<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $table = 'audit_logs';
    protected $fillable = [
        'user_id',
        'action',
        'target_table',
        'target_id',
        'data',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Get user who performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get logs by action
     */
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope to get logs by target table
     */
    public function scopeByTable($query, string $table)
    {
        return $query->where('target_table', $table);
    }

    /**
     * Scope to get logs by user
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope to get logs within date range
     */
    public function scopeInDateRange($query, string $startDate, string $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get formatted action description
     */
    public function getActionDescription(): string
    {
        $actions = [
            'create' => 'Created',
            'update' => 'Updated',
            'delete' => 'Deleted',
            'login' => 'Logged in',
            'logout' => 'Logged out',
            'view' => 'Viewed',
            'export' => 'Exported',
            'import' => 'Imported',
        ];

        return $actions[$this->action] ?? ucfirst($this->action);
    }

    /**
     * Get target object name
     */
    public function getTargetName(): string
    {
        $tables = [
            'users' => 'User',
            'roles' => 'Role',
            'permissions' => 'Permission',
            'projects' => 'Project',
            'tasks' => 'Task',
            'employees' => 'Employee',
            'departments' => 'Department',
            'payrolls' => 'Payroll',
            'leaves' => 'Leave',
            'devices' => 'Device',
            'documents' => 'Document',
        ];

        return $tables[$this->target_table] ?? ucfirst($this->target_table);
    }
} 