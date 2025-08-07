<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestDocument extends Model
{
    use HasFactory;

    protected $table = 'guest_documents';

    protected $fillable = [
        'guest_id',
        'title',
        'file_path',
        'file_type',
        'file_size',
        'description',
        'uploaded_by',
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
} 