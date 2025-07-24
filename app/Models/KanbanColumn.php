<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KanbanColumn extends Model
{
    protected $table = 'kanban_columns';
    protected $fillable = [
        'project_id',
        'name',
        'order',
    ];
} 