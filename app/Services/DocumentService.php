<?php

namespace App\Services;

use App\Models\Document;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    public function getAllDocuments($filters = [])
    {
        $query = Document::with(['creator', 'project', 'uploads']);

        // Apply filters
        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['project_id'])) {
            $query->where('project_id', $filters['project_id']);
        }

        if (isset($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->latest()->paginate(20);
    }

    public function getDocument($id)
    {
        return Document::with(['creator', 'project', 'uploads', 'blocks', 'versions', 'comments.user'])
                      ->findOrFail($id);
    }

    public function createDocument(array $data, $file)
    {
        try {
            // Create document record
            $document = Document::create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'category' => $data['category'],
                'status' => $data['status'],
                'tags' => isset($data['tags']) ? json_decode($data['tags'], true) : [],
                'project_id' => $data['project_id'] ?? null,
                'created_by' => Auth::id(),
                'visibility' => 'public', // Default visibility
            ]);

            // Handle file upload
            if ($file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->store('documents', 'public');

                // Create upload record
                Upload::create([
                    'uploaded_by' => Auth::id(),
                    'file_path' => $filePath,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                    'related_type' => 'document',
                    'related_id' => $document->id,
                ]);
            }

            // Load relationships for response
            return $document->load(['creator', 'project', 'uploads']);

        } catch (\Exception $e) {
            throw new \Exception('Failed to create document: ' . $e->getMessage());
        }
    }

    public function updateDocument($id, array $data)
    {
        $document = Document::findOrFail($id);
        
        $updateData = [];
        
        if (isset($data['title'])) $updateData['title'] = $data['title'];
        if (isset($data['description'])) $updateData['description'] = $data['description'];
        if (isset($data['category'])) $updateData['category'] = $data['category'];
        if (isset($data['status'])) $updateData['status'] = $data['status'];
        if (isset($data['tags'])) $updateData['tags'] = $data['tags'];
        if (isset($data['project_id'])) $updateData['project_id'] = $data['project_id'];

        $document->update($updateData);

        return $document->load(['creator', 'project', 'uploads']);
    }

    public function deleteDocument($id)
    {
        $document = Document::findOrFail($id);
        
        // Delete associated files
        foreach ($document->uploads as $upload) {
            Storage::disk('public')->delete($upload->file_path);
            $upload->delete();
        }

        $document->delete();
        
        return true;
    }

    public function getDocumentStats()
    {
        return [
            'total_documents' => Document::count(),
            'by_category' => [
                'project' => Document::where('category', 'project')->count(),
                'meeting' => Document::where('category', 'meeting')->count(),
                'policy' => Document::where('category', 'policy')->count(),
                'template' => Document::where('category', 'template')->count(),
                'other' => Document::where('category', 'other')->count(),
            ],
            'by_status' => [
                'draft' => Document::where('status', 'draft')->count(),
                'published' => Document::where('status', 'published')->count(),
                'archived' => Document::where('status', 'archived')->count(),
            ],
            'recent_uploads' => Document::where('created_at', '>=', now()->subDays(7))->count(),
            'total_size' => Upload::where('related_type', 'document')->sum('size'),
        ];
    }
}