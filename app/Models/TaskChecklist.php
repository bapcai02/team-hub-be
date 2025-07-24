<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskChecklist extends Model
{
    protected $table = 'task_checklists';
    protected $fillable = [
        'task_id',
        'title',
        'is_completed',
    ];
} 