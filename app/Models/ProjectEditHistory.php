<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectEditHistory extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'changes',
        'created_at',
        'updated_at',
    ];
} 