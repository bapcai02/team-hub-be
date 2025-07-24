<?php

namespace App\Interfaces\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
{
    public function authorize()
    {
        // Allow all authenticated users to upload files
        return true;
    }

    public function rules()
    {
        return [
            'file' => 'required|file|max:10240', // Max 10MB
        ];
    }
} 