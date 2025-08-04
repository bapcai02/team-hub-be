<?php

namespace App\Interfaces\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CheckOutRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'notes.string' => 'Notes must be a string',
            'notes.max' => 'Notes cannot exceed 500 characters',
        ];
    }
}