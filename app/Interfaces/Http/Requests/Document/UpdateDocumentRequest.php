<?php

namespace App\Interfaces\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'sometimes|string|max:255',
            'parent_id' => 'sometimes|integer|exists:documents,id',
            'visibility' => 'sometimes|in:public,private,department',
        ];
    }
} 