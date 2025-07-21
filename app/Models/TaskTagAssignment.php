<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskTagAssignment extends Model
{
    protected $table = 'task_tag_assignments';
    protected $fillable = [
        'task_id',
        'task_tag_id',
    ];
} 