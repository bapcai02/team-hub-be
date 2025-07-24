<?php

namespace App\Interfaces\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Application\Project\Services\ProjectService;
use App\Interfaces\Http\Requests\Project\StoreProjectRequest;
use Illuminate\Http\Request;

class ProjectController
{
    public function __construct(protected ProjectService $projectService) {}

    public function store(StoreProjectRequest $request)
    {
        try {
            $data = $request->validated();
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $path = $file->store('projects');
                $data['document'] = $path;
            }
            $members = $data['members'] ?? [];
            unset($data['members']);
            $project = $this->projectService->createProjectWithMembers($data, $members);
            return ApiResponseHelper::responseApi(['project' => $project], 'project_create_success', 201);
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $userId = $request->query('user_id');
            if (!$userId) {
                return ApiResponseHelper::responseApi([], 'user_id_required', 400);
            }
            $projects = $this->projectService->getProjectsByUserId($userId);
            return ApiResponseHelper::responseApi(['projects' => $projects], 'project_list_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }
} 