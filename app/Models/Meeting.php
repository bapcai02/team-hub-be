<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meeting extends Model
{
    use HasFactory, SoftDeletes;

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

    protected $casts = [
        'start_time' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants()
    {
        return $this->hasMany(MeetingParticipant::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'meeting_participants', 'meeting_id', 'user_id');
    }
} 