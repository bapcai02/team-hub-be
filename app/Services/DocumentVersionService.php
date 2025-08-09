<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\Upload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentVersionService
{
    public function getVersions($documentId)
    {
        return DocumentVersion::where('document_id', $documentId)
            ->with(['creator'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($version) {
                // Get version info from uploads table
                $upload = Upload::where('related_type', 'document_version')
                    ->where('related_id', $version->id)
                    ->first();

                return [
                    'id' => $version->id,
                    'document_id' => $version->document_id,
                    'version' => $this->getVersionNumber($version),
                    'content_snapshot' => $version->content_snapshot,
                    'hash' => $version->hash,
                    'created_by' => [
                        'id' => $version->creator->id,
                        'name' => $version->creator->name,
                        'email' => $version->creator->email,
                    ],
                    'created_at' => $version->created_at,
                    'file_name' => $upload->original_name ?? null,
                    'file_size' => $upload->size ?? 0,
                    'file_path' => $upload->file_path ?? null,
                    'mime_type' => $upload->mime_type ?? null,
                    'change_log' => $this->getChangeLog($version),
                    'is_current' => $this->isCurrentVersion($version),
                ];
            });
    }

    public function createVersion($documentId, $file, $changeLog = null)
    {
        $document = Document::findOrFail($documentId);
        
        // Create content snapshot (could be file content or metadata)
        $contentSnapshot = json_encode([
            'title' => $document->title,
            'description' => $document->description,
            'category' => $document->category,
            'status' => $document->status,
            'tags' => $document->tags,
            'file_info' => [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'type' => $file->getClientMimeType(),
            ]
        ]);

        // Generate hash for version
        $hash = hash('sha256', $contentSnapshot . time());

        // Create version record
        $version = DocumentVersion::create([
            'document_id' => $documentId,
            'content_snapshot' => $contentSnapshot,
            'hash' => $hash,
            'created_by' => Auth::id(),
        ]);

        // Store the file
        $fileName = time() . '_v' . $version->id . '_' . $file->getClientOriginalName();
        $filePath = $file->store('document_versions', 'public');

        // Create upload record for this version
        Upload::create([
            'uploaded_by' => Auth::id(),
            'file_path' => $filePath,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'related_type' => 'document_version',
            'related_id' => $version->id,
        ]);

        // Store change log in content_snapshot if provided
        if ($changeLog) {
            $content = json_decode($version->content_snapshot, true);
            $content['change_log'] = $changeLog;
            $version->update(['content_snapshot' => json_encode($content)]);
        }

        return $version->load('creator');
    }

    public function deleteVersion($versionId)
    {
        $version = DocumentVersion::findOrFail($versionId);
        
        // Don't allow deleting if it's the only version
        $versionCount = DocumentVersion::where('document_id', $version->document_id)->count();
        if ($versionCount <= 1) {
            throw new \Exception('Cannot delete the only version of a document');
        }

        // Delete associated file
        $upload = Upload::where('related_type', 'document_version')
            ->where('related_id', $versionId)
            ->first();
            
        if ($upload) {
            Storage::disk('public')->delete($upload->file_path);
            $upload->delete();
        }

        $version->delete();
        return true;
    }

    private function getVersionNumber($version)
    {
        // Calculate version number based on creation order
        $olderVersions = DocumentVersion::where('document_id', $version->document_id)
            ->where('created_at', '<=', $version->created_at)
            ->count();
        
        return $olderVersions;
    }

    private function getChangeLog($version)
    {
        $content = json_decode($version->content_snapshot, true);
        return $content['change_log'] ?? 'No change log provided';
    }

    private function isCurrentVersion($version)
    {
        // The latest version is the current one
        $latestVersion = DocumentVersion::where('document_id', $version->document_id)
            ->orderBy('created_at', 'desc')
            ->first();
            
        return $latestVersion && $latestVersion->id === $version->id;
    }
}