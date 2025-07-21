<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    protected $table = 'event_participants';
    protected $fillable = [
        'event_id',
        'user_id',
        'status',
    ];
} 