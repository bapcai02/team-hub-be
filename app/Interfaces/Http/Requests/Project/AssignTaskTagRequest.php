<?php

namespace App\Interfaces\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class AssignTaskTagRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tag_ids' => 'required|array',
            'tag_ids.*' => 'integer|exists:task_tags,id',
        ];
    }

    public function messages()
    {
        return [
            'tag_ids.required' => 'Tag IDs are required',
            'tag_ids.array' => 'Tag IDs must be an array',
            'tag_ids.*.integer' => 'Each tag ID must be an integer',
            'tag_ids.*.exists' => 'One or more tag IDs do not exist',
        ];
    }
}