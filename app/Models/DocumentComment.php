<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentComment extends Model
{
    protected $table = 'document_comments';
    protected $fillable = [
        'document_id',
        'block_id',
        'user_id',
        'comment',
        'created_at',
    ];
    public $timestamps = false;
} 