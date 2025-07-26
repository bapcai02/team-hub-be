<?php

namespace App\Interfaces\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class StoreKanbanColumnRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'project_id' => 'required|integer|exists:projects,id',
            'name' => 'required|string|max:255',
            'order' => 'sometimes|integer|min:0',
        ];
    }
} 