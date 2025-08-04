<?php

namespace App\Interfaces\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'project_id' => 'required|integer|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|integer|exists:users,id',
            'status' => 'required|in:todo,in_progress,done,backlog',
            'priority' => 'required|in:low,medium,high',
            'deadline' => 'nullable|date|after:today',
        ];
    }
} 