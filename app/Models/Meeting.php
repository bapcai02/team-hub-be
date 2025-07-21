<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $table = 'meetings';
    protected $fillable = [
        'conversation_id',
        'title',
        'description',
        'start_time',
        'duration_minutes',
        'link',
        'status',
        'created_by',
    ];
} 