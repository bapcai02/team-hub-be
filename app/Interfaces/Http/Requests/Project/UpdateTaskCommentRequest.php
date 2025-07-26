<?php

namespace App\Interfaces\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskCommentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'content' => 'required|string|max:2000',
        ];
    }

    public function messages()
    {
        return [
            'content.required' => 'Comment content is required',
            'content.string' => 'Comment content must be a string',
            'content.max' => 'Comment content cannot exceed 2000 characters',
        ];
    }
}