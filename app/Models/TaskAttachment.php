<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskAttachment extends Model
{
    protected $table = 'task_attachments';
    protected $fillable = [
        'task_id',
        'file_path',
        'uploaded_by',
        'uploaded_at',
    ];
} 