<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentShare;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DocumentShareService
{
    public function getSharedUsers($documentId)
    {
        $document = Document::findOrFail($documentId);
        
        return DocumentShare::where('document_id', $documentId)
            ->with(['user', 'sharedBy'])
            ->active()
            ->get()
            ->map(function ($share) {
                return [
                    'id' => $share->id,
                    'user' => [
                        'id' => $share->user->id,
                        'name' => $share->user->name,
                        'email' => $share->user->email,
                    ],
                    'permission' => $share->permission,
                    'shared_by' => [
                        'id' => $share->sharedBy->id,
                        'name' => $share->sharedBy->name,
                    ],
                    'shared_at' => $share->shared_at,
                    'expires_at' => $share->expires_at,
                    'can_view' => $share->canView(),
                    'can_edit' => $share->canEdit(),
                    'can_comment' => $share->canComment(),
                ];
            });
    }

    public function shareWithUser($documentId, $userId, $permission = 'view', $expiresAt = null)
    {
        $document = Document::findOrFail($documentId);
        $user = User::findOrFail($userId);

        // Check if already shared
        $existingShare = DocumentShare::where('document_id', $documentId)
            ->where('user_id', $userId)
            ->first();

        if ($existingShare) {
            // Update existing share
            $existingShare->update([
                'permission' => $permission,
                'shared_by' => Auth::id(),
                'shared_at' => now(),
                'expires_at' => $expiresAt ? Carbon::parse($expiresAt) : null,
                'deleted_at' => null, // Restore if soft deleted
            ]);
            
            return $existingShare->load(['user', 'sharedBy']);
        }

        // Create new share
        $share = DocumentShare::create([
            'document_id' => $documentId,
            'user_id' => $userId,
            'permission' => $permission,
            'shared_by' => Auth::id(),
            'shared_at' => now(),
            'expires_at' => $expiresAt ? Carbon::parse($expiresAt) : null,
        ]);

        return $share->load(['user', 'sharedBy']);
    }

    public function shareWithMultipleUsers($documentId, $userIds, $permission = 'view', $expiresAt = null)
    {
        $results = [];
        
        foreach ($userIds as $userId) {
            try {
                $share = $this->shareWithUser($documentId, $userId, $permission, $expiresAt);
                $results[] = $share;
            } catch (\Exception $e) {
                // Log error but continue with other users
                \Illuminate\Support\Facades\Log::error("Failed to share document {$documentId} with user {$userId}: " . $e->getMessage());
            }
        }

        return $results;
    }

    public function unshareWithUser($documentId, $userId)
    {
        $share = DocumentShare::where('document_id', $documentId)
            ->where('user_id', $userId)
            ->first();

        if (!$share) {
            throw new \Exception('Share not found');
        }

        $share->delete(); // Soft delete
        return true;
    }

    public function updatePermission($documentId, $userId, $permission)
    {
        $share = DocumentShare::where('document_id', $documentId)
            ->where('user_id', $userId)
            ->first();

        if (!$share) {
            throw new \Exception('Share not found');
        }

        $share->update([
            'permission' => $permission,
            'shared_by' => Auth::id(),
        ]);

        return $share->load(['user', 'sharedBy']);
    }

    public function getDocumentsSharedWithUser($userId, $permission = null)
    {
        $query = DocumentShare::where('user_id', $userId)
            ->with(['document.creator', 'document.uploads'])
            ->active();

        if ($permission) {
            $query->byPermission($permission);
        }

        return $query->get()->map(function ($share) {
            return [
                'share_id' => $share->id,
                'permission' => $share->permission,
                'shared_at' => $share->shared_at,
                'expires_at' => $share->expires_at,
                'document' => [
                    'id' => $share->document->id,
                    'title' => $share->document->title,
                    'description' => $share->document->description,
                    'category' => $share->document->category,
                    'status' => $share->document->status,
                    'created_by' => $share->document->creator->name ?? 'Unknown',
                    'created_at' => $share->document->created_at,
                ],
            ];
        });
    }

    public function getUserPermission($documentId, $userId)
    {
        $share = DocumentShare::where('document_id', $documentId)
            ->where('user_id', $userId)
            ->active()
            ->first();

        if (!$share) {
            return null;
        }

        return [
            'permission' => $share->permission,
            'can_view' => $share->canView(),
            'can_edit' => $share->canEdit(),
            'can_comment' => $share->canComment(),
        ];
    }

    public function searchUsers($query, $excludeUserIds = [])
    {
        return User::where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->whereNotIn('id', $excludeUserIds)
            ->limit(10)
            ->select(['id', 'name', 'email'])
            ->get();
    }
}