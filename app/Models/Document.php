<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;
    protected $table = 'documents';
    protected $fillable = [
        'title',
        'parent_id',
        'created_by',
        'visibility',
    ];
} 