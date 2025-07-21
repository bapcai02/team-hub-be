<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    protected $table = 'search_histories';
    protected $fillable = [
        'user_id',
        'query',
        'filters',
        'created_at',
    ];
    public $timestamps = false;
} 