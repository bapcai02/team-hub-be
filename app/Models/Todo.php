<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $table = 'todos';
    protected $fillable = [
        'user_id',
        'title',
        'due_date',
        'is_done',
    ];
} 