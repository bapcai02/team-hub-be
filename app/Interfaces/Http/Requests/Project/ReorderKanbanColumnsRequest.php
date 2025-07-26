<?php

namespace App\Interfaces\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class ReorderKanbanColumnsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'column_ids' => 'required|array|min:1',
            'column_ids.*' => 'integer|exists:kanban_columns,id',
        ];
    }
} 