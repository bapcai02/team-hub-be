<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';
    protected $fillable = [
        'title',
        'type',
        'filters',
        'generated_by',
        'generated_at',
        'file_path',
    ];
} 