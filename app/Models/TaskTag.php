<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskTag extends Model
{
    protected $table = 'task_tags';
    protected $fillable = [
        'name',
        'color',
    ];
} 