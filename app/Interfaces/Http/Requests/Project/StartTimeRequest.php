<?php

namespace App\Interfaces\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class StartTimeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'task_id' => 'required|integer|exists:tasks,id',
            'note' => 'nullable|string|max:500',
        ];
    }
} 