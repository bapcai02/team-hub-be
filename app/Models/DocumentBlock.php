<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentBlock extends Model
{
    protected $table = 'document_blocks';
    protected $fillable = [
        'document_id',
        'type',
        'content',
        'order',
        'created_at',
    ];
    public $timestamps = false;
} 