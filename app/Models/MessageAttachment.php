<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageAttachment extends Model
{
    protected $table = 'message_attachments';
    protected $fillable = [
        'message_id',
        'file_path',
        'file_type',
        'uploaded_by',
        'uploaded_at',
    ];
    public $timestamps = false;
} 