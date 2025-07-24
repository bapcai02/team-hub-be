<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;
    protected $table = 'events';
    protected $fillable = [
        'title',
        'description',
        'owner_id',
        'start_time',
        'end_time',
        'type',
        'visibility',
    ];
} 