<?php

namespace App\Interfaces\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Application\Document\Services\DocumentService;
use App\Interfaces\Http\Requests\Document\StoreDocumentRequest;
use App\Interfaces\Http\Requests\Document\UpdateDocumentRequest;
use Illuminate\Http\Request;

class DocumentController
{
    public function __construct(protected DocumentService $documentService) {}

    /**
     * Create a new document.
     */
    public function store(StoreDocumentRequest $request)
    {
        try {
            $data = $request->validated();
            $data['created_by'] = $request->user()->id;
            
            $document = $this->documentService->create($data);
            return ApiResponseHelper::responseApi(['document' => $document], 'document_create_success', 201);
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get all documents for current user.
     */
    public function index(Request $request)
    {
        try {
            $userId = $request->user()->id;
            $documents = $this->documentService->getByUserId($userId);
            return ApiResponseHelper::responseApi(['documents' => $documents], 'document_list_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get document details by ID.
     */
    public function show($id)
    {
        try {
            $document = $this->documentService->find($id);
            if (!$document) {
                return ApiResponseHelper::responseApi([], 'document_not_found', 404);
            }
            return ApiResponseHelper::responseApi(['document' => $document], 'document_get_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Update document details.
     */
    public function update(UpdateDocumentRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $document = $this->documentService->update($id, $data);
            
            if (!$document) {
                return ApiResponseHelper::responseApi([], 'document_not_found', 404);
            }
            return ApiResponseHelper::responseApi(['document' => $document], 'document_update_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Delete document.
     */
    public function destroy($id)
    {
        try {
            $success = $this->documentService->delete($id);
            if (!$success) {
                return ApiResponseHelper::responseApi([], 'document_not_found', 404);
            }
            return ApiResponseHelper::responseApi([], 'document_delete_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get documents by visibility.
     */
    public function getByVisibility(Request $request)
    {
        try {
            $visibility = $request->query('visibility');
            if (!$visibility) {
                return ApiResponseHelper::responseApi([], 'visibility_required', 400);
            }
            
            $documents = $this->documentService->getByVisibility($visibility);
            return ApiResponseHelper::responseApi(['documents' => $documents], 'document_visibility_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get root documents (no parent).
     */
    public function getRootDocuments()
    {
        try {
            $documents = $this->documentService->getRootDocuments();
            return ApiResponseHelper::responseApi(['documents' => $documents], 'document_root_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get child documents of a parent.
     */
    public function getChildren($parentId)
    {
        try {
            $documents = $this->documentService->getChildren($parentId);
            return ApiResponseHelper::responseApi(['documents' => $documents], 'document_children_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }
} 