<?php

namespace App\Interfaces\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKanbanColumnRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'order' => 'sometimes|integer|min:0',
        ];
    }
} 