<?php

namespace App\Interfaces\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Helpers\ApiResponseHelper;
use App\Services\DocumentService;
use App\Services\DocumentVersionService;
use App\Services\DocumentShareService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    protected $documentService;
    protected $versionService;
    protected $shareService;

    public function __construct(
        DocumentService $documentService, 
        DocumentVersionService $versionService,
        DocumentShareService $shareService
    ) {
        $this->documentService = $documentService;
        $this->versionService = $versionService;
        $this->shareService = $shareService;
    }

    public function index(Request $request)
    {
        try {
            $filters = $request->only(['category', 'status', 'project_id', 'search']);
            $documents = $this->documentService->getAllDocuments($filters);

            return ApiResponseHelper::success('documents_retrieved', $documents);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('documents_retrieval_failed', $e->getMessage());
        }
    }

    public function getStats()
    {
        try {
            $stats = $this->documentService->getDocumentStats();
            return ApiResponseHelper::success('document_stats_retrieved', $stats);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('document_stats_retrieval_failed', $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            $searchQuery = $request->get('q', '');
            $filters = ['search' => $searchQuery];
            $documents = $this->documentService->getAllDocuments($filters);

            return ApiResponseHelper::success('documents_search_completed', $documents);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('documents_search_failed', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $document = $this->documentService->getDocument($id);
            return ApiResponseHelper::success('document_retrieved', $document);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('document_retrieval_failed', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'required|string|in:project,meeting,policy,template,other',
                'status' => 'required|string|in:draft,published,archived',
                'tags' => 'nullable|string',
                'project_id' => 'nullable|integer|exists:projects,id',
                'file' => 'required|file|max:51200', // 50MB max
            ]);

            if ($validator->fails()) {
                return ApiResponseHelper::error('validation_failed', $validator->errors()->first());
            }

            $data = $request->only(['title', 'description', 'category', 'status', 'tags', 'project_id']);
            $file = $request->file('file');
            
            $document = $this->documentService->createDocument($data, $file);

            return ApiResponseHelper::success('document_created', $document);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('document_creation_failed', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'sometimes|required|string|in:project,meeting,policy,template,other',
                'status' => 'sometimes|required|string|in:draft,published,archived',
                'tags' => 'nullable|array',
                'project_id' => 'nullable|integer|exists:projects,id',
            ]);

            if ($validator->fails()) {
                return ApiResponseHelper::error('validation_failed', $validator->errors()->first());
            }

            $data = $request->only(['title', 'description', 'category', 'status', 'tags', 'project_id']);
            $document = $this->documentService->updateDocument($id, $data);

            return ApiResponseHelper::success('document_updated', $document);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('document_update_failed', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->documentService->deleteDocument($id);
            return ApiResponseHelper::success('document_deleted', ['id' => $id]);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('document_deletion_failed', $e->getMessage());
        }
    }

    public function download($id)
    {
        try {
            // Mock file download
            $document = [
                'id' => $id,
                'file_name' => 'project_requirements.pdf',
                'file_path' => 'documents/project_requirements.pdf',
            ];

            return ApiResponseHelper::success('document_download_ready', $document);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('document_download_failed', $e->getMessage());
        }
    }

    public function addComment(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'content' => 'required|string|max:1000',
            ]);

            if ($validator->fails()) {
                return ApiResponseHelper::error('validation_failed', $validator->errors()->first());
            }

            $comment = [
                'id' => rand(100, 999),
                'document_id' => $id,
                'content' => $request->content,
                'user' => ['name' => Auth::user()->name],
                'created_at' => now(),
            ];

            return ApiResponseHelper::success('comment_added', $comment);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('comment_addition_failed', $e->getMessage());
        }
    }

    public function getComments($id)
    {
        try {
            $comments = collect([
                [
                    'id' => 1,
                    'document_id' => $id,
                    'content' => 'Great document! Very helpful for the project.',
                    'user' => ['name' => Auth::user()->name],
                    'created_at' => now()->subDays(2),
                ],
                [
                    'id' => 2,
                    'document_id' => $id,
                    'content' => 'Please update the requirements section.',
                    'user' => ['name' => Auth::user()->name],
                    'created_at' => now()->subDays(1),
                ],
            ]);

            return ApiResponseHelper::success('comments_retrieved', $comments);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('comments_retrieval_failed', $e->getMessage());
        }
    }

    // Document Version Management
    public function getVersions($id)
    {
        try {
            $versions = $this->versionService->getVersions($id);
            return ApiResponseHelper::success('versions_retrieved', $versions);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('versions_retrieval_failed', $e->getMessage());
        }
    }

    public function createVersion(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|max:51200', // 50MB max
                'change_log' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return ApiResponseHelper::error('validation_failed', $validator->errors()->first());
            }

            $file = $request->file('file');
            $changeLog = $request->input('change_log');
            
            $version = $this->versionService->createVersion($id, $file, $changeLog);
            return ApiResponseHelper::success('version_created', $version);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('version_creation_failed', $e->getMessage());
        }
    }

    public function deleteVersion($documentId, $versionId)
    {
        try {
            $this->versionService->deleteVersion($versionId);
            return ApiResponseHelper::success('version_deleted', ['id' => $versionId]);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('version_deletion_failed', $e->getMessage());
        }
    }

    // Document Share Management
    public function getSharedUsers($id)
    {
        try {
            $sharedUsers = $this->shareService->getSharedUsers($id);
            return ApiResponseHelper::success('shared_users_retrieved', $sharedUsers);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('shared_users_retrieval_failed', $e->getMessage());
        }
    }

    public function shareDocument(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_ids' => 'required|array|min:1',
                'user_ids.*' => 'integer|exists:users,id',
                'permission' => 'required|string|in:view,edit,comment',
                'expires_at' => 'nullable|date|after:now',
            ]);

            if ($validator->fails()) {
                return ApiResponseHelper::error('validation_failed', $validator->errors()->first());
            }

            $userIds = $request->input('user_ids');
            $permission = $request->input('permission', 'view');
            $expiresAt = $request->input('expires_at');

            if (count($userIds) === 1) {
                $share = $this->shareService->shareWithUser($id, $userIds[0], $permission, $expiresAt);
                return ApiResponseHelper::success('document_shared', $share);
            } else {
                $shares = $this->shareService->shareWithMultipleUsers($id, $userIds, $permission, $expiresAt);
                return ApiResponseHelper::success('document_shared', $shares);
            }
        } catch (\Exception $e) {
            return ApiResponseHelper::error('document_share_failed', $e->getMessage());
        }
    }

    public function unshareDocument(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|exists:users,id',
            ]);

            if ($validator->fails()) {
                return ApiResponseHelper::error('validation_failed', $validator->errors()->first());
            }

            $userId = $request->input('user_id');
            $this->shareService->unshareWithUser($id, $userId);
            
            return ApiResponseHelper::success('document_unshared', ['user_id' => $userId]);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('document_unshare_failed', $e->getMessage());
        }
    }

    public function updateSharePermission(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|exists:users,id',
                'permission' => 'required|string|in:view,edit,comment',
            ]);

            if ($validator->fails()) {
                return ApiResponseHelper::error('validation_failed', $validator->errors()->first());
            }

            $userId = $request->input('user_id');
            $permission = $request->input('permission');
            
            $share = $this->shareService->updatePermission($id, $userId, $permission);
            return ApiResponseHelper::success('permission_updated', $share);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('permission_update_failed', $e->getMessage());
        }
    }

    public function searchUsers(Request $request)
    {
        try {
            $query = $request->get('q', '');
            $excludeUserIds = $request->get('exclude', []);
            
            $users = $this->shareService->searchUsers($query, $excludeUserIds);
            return ApiResponseHelper::success('users_found', $users);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('user_search_failed', $e->getMessage());
        }
    }
} 