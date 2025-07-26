<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskAttachment extends Model
{
    use SoftDeletes;
    
    protected $table = 'task_attachments';
    protected $fillable = [
        'task_id',
        'file_path',
        'uploaded_by',
        'uploaded_at',
        'original_name',
        'file_size',
        'mime_type',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}