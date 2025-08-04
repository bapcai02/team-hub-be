<?php

namespace App\Interfaces\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'assigned_to' => 'sometimes|integer|exists:users,id',
            'status' => 'sometimes|in:todo,in_progress,done,backlog',
            'priority' => 'sometimes|in:low,medium,high',
            'deadline' => 'sometimes|date|after:today',
        ];
    }
} 