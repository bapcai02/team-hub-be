<?php

namespace App\Interfaces\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskTagRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:50|unique:task_tags,name',
            'color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tag name is required',
            'name.string' => 'Tag name must be a string',
            'name.max' => 'Tag name cannot exceed 50 characters',
            'name.unique' => 'Tag name already exists',
            'color.string' => 'Color must be a string',
            'color.max' => 'Color cannot exceed 7 characters',
            'color.regex' => 'Color must be a valid hex color code (e.g., #FF0000)',
        ];
    }
}