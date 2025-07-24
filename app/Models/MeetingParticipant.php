<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingParticipant extends Model
{
    protected $table = 'meeting_participants';
    protected $fillable = [
        'meeting_id',
        'user_id',
        'joined_at',
        'left_at',
    ];
    public $timestamps = false;
} 