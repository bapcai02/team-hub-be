<?php

namespace App\Interfaces\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskAttachmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => 'required|file|max:10240', // Max 10MB
        ];
    }

    public function messages()
    {
        return [
            'file.required' => 'File is required',
            'file.file' => 'Must be a valid file',
            'file.max' => 'File size cannot exceed 10MB',
        ];
    }
}