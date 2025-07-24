<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Upload extends Model
{
    use SoftDeletes;
    protected $table = 'uploads';
    protected $fillable = [
        'uploaded_by',
        'file_path',
        'original_name',
        'mime_type',
        'size',
        'related_type',
        'related_id',
        'created_at',
    ];
    public $timestamps = false;
} 