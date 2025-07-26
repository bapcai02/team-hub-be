<?php

namespace App\Interfaces\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'parent_id' => 'nullable|integer|exists:documents,id',
            'visibility' => 'required|in:public,private,department',
        ];
    }
} 