<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use SoftDeletes;
    
    protected $table = 'attendances';
    protected $fillable = [
        'employee_id',
        'date',
        'check_in_time',
        'check_out_time',
        'break_start_time',
        'break_end_time',
        'total_hours',
        'overtime_hours',
        'status',
        'notes',
        'location',
        'ip_address',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'break_start_time' => 'datetime',
        'break_end_time' => 'datetime',
        'total_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function user()
    {
        return $this->hasOneThrough(User::class, Employee::class, 'id', 'id', 'employee_id', 'user_id');
    }
}