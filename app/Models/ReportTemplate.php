<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportTemplate extends Model
{
    protected $table = 'report_templates';
    protected $fillable = [
        'name',
        'type',
        'structure',
        'created_by',
    ];
} 