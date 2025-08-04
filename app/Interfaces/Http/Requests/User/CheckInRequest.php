<?php

namespace App\Interfaces\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CheckInRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'location.string' => 'Location must be a string',
            'location.max' => 'Location cannot exceed 255 characters',
            'notes.string' => 'Notes must be a string',
            'notes.max' => 'Notes cannot exceed 500 characters',
        ];
    }
}