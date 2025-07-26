<?php

namespace App\Interfaces\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Application\Project\Services\ProjectService;
use App\Interfaces\Http\Requests\Project\StoreProjectRequest;
use App\Interfaces\Http\Requests\Project\UpdateProjectRequest;
use App\Interfaces\Http\Requests\Project\AddMembersRequest;
use App\Interfaces\Http\Requests\Project\RemoveMembersRequest;
use Illuminate\Http\Request;

class ProjectController
{
    public function __construct(protected ProjectService $projectService) {}

    /**
     * Create a new project with members.
     */
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

    /**
     * Get all projects for a user.
     */
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

    /**
     * Get project details by ID.
     */
    public function show($id)
    {
        try {
            $project = $this->projectService->find($id);
            if (!$project) {
                return ApiResponseHelper::responseApi([], 'project_not_found', 404);
            }
            return ApiResponseHelper::responseApi(['project' => $project], 'project_get_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Update project details.
     */
    public function update(UpdateProjectRequest $request, $id)
    {
        try {
            $data = $request->validated();
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $path = $file->store('projects');
                $data['document'] = $path;
            }

            $project = $this->projectService->update($id, $data);
            if (!$project) {
                return ApiResponseHelper::responseApi([], 'project_not_found', 404);
            }
            return ApiResponseHelper::responseApi(['project' => $project], 'project_update_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Delete project.
     */
    public function destroy($id)
    {
        try {
            $success = $this->projectService->delete($id);
            if (!$success) {
                return ApiResponseHelper::responseApi([], 'project_not_found', 404);
            }
            return ApiResponseHelper::responseApi([], 'project_delete_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get project statistics.
     */
    public function statistics($id)
    {
        try {
            $stats = $this->projectService->getProjectStatistics($id);
            return ApiResponseHelper::responseApi(['statistics' => $stats], 'project_statistics_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Add members to project.
     */
    public function addMembers(AddMembersRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $memberIds = $data['member_ids'];
            $role = $data['role'] ?? 'member';
            
            $this->projectService->addMembersToProject($id, $memberIds, $role);
            return ApiResponseHelper::responseApi([], 'project_members_added_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Remove members from project.
     */
    public function removeMembers(RemoveMembersRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $memberIds = $data['member_ids'];
            
            $this->projectService->removeMembersFromProject($id, $memberIds);
            return ApiResponseHelper::responseApi([], 'project_members_removed_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get project members.
     */
    public function members($id)
    {
        try {
            $members = $this->projectService->getProjectMembers($id);
            return ApiResponseHelper::responseApi(['members' => $members], 'project_members_success');
        } catch (\Throwable $e) {
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }
} 