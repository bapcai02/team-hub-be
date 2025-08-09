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
        'description',
        'category',
        'status',
        'tags',
        'parent_id',
        'created_by',
        'visibility',
        'project_id',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function parent()
    {
        return $this->belongsTo(Document::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Document::class, 'parent_id');
    }

    public function uploads()
    {
        return $this->hasMany(Upload::class, 'related_id')->where('related_type', 'document');
    }

    public function blocks()
    {
        return $this->hasMany(DocumentBlock::class);
    }

    public function versions()
    {
        return $this->hasMany(DocumentVersion::class);
    }

    public function comments()
    {
        return $this->hasMany(DocumentComment::class);
    }

    public function shares()
    {
        return $this->hasMany(DocumentShare::class);
    }

    public function sharedUsers()
    {
        return $this->belongsToMany(User::class, 'document_shares')
                    ->withPivot(['permission', 'shared_by', 'shared_at', 'expires_at'])
                    ->withTrashed();
    }
} 