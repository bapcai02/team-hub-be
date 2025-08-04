<?php

namespace App\Interfaces\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'status' => 'sometimes|in:active,paused,completed,cancelled',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
            'total_amount' => 'sometimes|numeric',
            'document' => 'sometimes|file|max:10240', // 10MB
        ];
    }
} 