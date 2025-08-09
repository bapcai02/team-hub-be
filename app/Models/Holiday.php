<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $table = 'holidays';

    protected $fillable = [
        'name',
        'date',
        'type',
        'description',
        'is_paid',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'is_paid' => 'boolean',
        'is_active' => 'boolean',
    ];
} 