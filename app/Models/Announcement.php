<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $table = 'announcements';
    protected $fillable = [
        'title',
        'content',
        'visible_to',
        'start_date',
        'end_date',
        'created_by',
    ];
} 