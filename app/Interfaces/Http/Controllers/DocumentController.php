<?php

namespace App\Interfaces\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Helpers\ApiResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $documents = collect([
                [
                    'id' => 1,
                    'title' => 'Project Requirements',
                    'description' => 'Detailed project requirements document',
                    'file_name' => 'project_requirements.pdf',
                    'file_type' => 'pdf',
                    'file_size' => 1024000,
                    'category' => 'project',
                    'status' => 'published',
                    'user' => ['name' => $user->name],
                    'created_at' => now()->subDays(5),
                    'updated_at' => now()->subDays(2),
                ],
                [
                    'id' => 2,
                    'title' => 'Meeting Notes',
                    'description' => 'Notes from team meeting',
                    'file_name' => 'meeting_notes.docx',
                    'file_type' => 'docx',
                    'file_size' => 512000,
                    'category' => 'meeting',
                    'status' => 'draft',
                    'user' => ['name' => $user->name],
                    'created_at' => now()->subDays(3),
                    'updated_at' => now()->subDays(1),
                ],
                [
                    'id' => 3,
                    'title' => 'Company Policy',
                    'description' => 'Updated company policies',
                    'file_name' => 'company_policy.pdf',
                    'file_type' => 'pdf',
                    'file_size' => 2048000,
                    'category' => 'policy',
                    'status' => 'published',
                    'user' => ['name' => $user->name],
                    'created_at' => now()->subDays(10),
                    'updated_at' => now()->subDays(8),
                ],
            ]);

            return ApiResponseHelper::success('documents_retrieved', $documents);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('documents_retrieval_failed', $e->getMessage());
        }
    }

    public function getStats()
    {
        try {
            $stats = [
                'total' => 3,
                'total_size' => 3584000,
                'recent_uploads' => 1,
                'by_status' => [
                    'published' => 2,
                    'draft' => 1,
                    'archived' => 0,
                ],
                'by_category' => [
                    'project' => 1,
                    'meeting' => 1,
                    'policy' => 1,
                    'template' => 0,
                    'other' => 0,
                ],
            ];

            return ApiResponseHelper::success('document_stats_retrieved', $stats);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('document_stats_retrieval_failed', $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            $query = $request->get('q', '');
            
            // Mock search results
            $documents = collect([
                [
                    'id' => 1,
                    'title' => 'Project Requirements',
                    'description' => 'Detailed project requirements document',
                    'file_name' => 'project_requirements.pdf',
                    'file_type' => 'pdf',
                    'file_size' => 1024000,
                    'category' => 'project',
                    'status' => 'published',
                    'user' => ['name' => Auth::user()->name],
                    'created_at' => now()->subDays(5),
                ],
            ])->filter(function ($doc) use ($query) {
                return stripos($doc['title'], $query) !== false || 
                       stripos($doc['description'], $query) !== false;
            });

            return ApiResponseHelper::success('documents_search_completed', $documents);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('documents_search_failed', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $document = [
                'id' => $id,
                'title' => 'Project Requirements',
                'description' => 'Detailed project requirements document',
                'file_name' => 'project_requirements.pdf',
                'file_type' => 'pdf',
                'file_size' => 1024000,
                'category' => 'project',
                'status' => 'published',
                'user' => ['name' => Auth::user()->name],
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(2),
            ];

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
                'file' => 'required|file|max:51200', // 50MB max
            ]);

            if ($validator->fails()) {
                return ApiResponseHelper::error('validation_failed', $validator->errors()->first());
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Mock document creation
            $document = [
                'id' => rand(100, 999),
                'title' => $request->title,
                'description' => $request->description,
                'file_name' => $fileName,
                'file_type' => $file->getClientOriginalExtension(),
                'file_size' => $file->getSize(),
                'category' => $request->category,
                'status' => $request->status,
                'user' => ['name' => Auth::user()->name],
                'created_at' => now(),
                'updated_at' => now(),
            ];

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
            ]);

            if ($validator->fails()) {
                return ApiResponseHelper::error('validation_failed', $validator->errors()->first());
            }

            // Mock document update
            $document = [
                'id' => $id,
                'title' => $request->title ?? 'Updated Document',
                'description' => $request->description,
                'file_name' => 'project_requirements.pdf',
                'file_type' => 'pdf',
                'file_size' => 1024000,
                'category' => $request->category ?? 'project',
                'status' => $request->status ?? 'published',
                'user' => ['name' => Auth::user()->name],
                'created_at' => now()->subDays(5),
                'updated_at' => now(),
            ];

            return ApiResponseHelper::success('document_updated', $document);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('document_update_failed', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // Mock document deletion
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
} 