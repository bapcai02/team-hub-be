<?php

namespace App\Interfaces\Http\Requests\Holiday;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHolidayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'date' => 'sometimes|date',
            'type' => 'sometimes|in:national,company,regional',
            'description' => 'sometimes|string|max:1000',
            'is_paid' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.string' => 'Holiday name must be a string.',
            'name.max' => 'Holiday name cannot exceed 255 characters.',
            'date.date' => 'Holiday date must be a valid date.',
            'type.in' => 'Invalid holiday type.',
            'description.string' => 'Description must be a string.',
            'description.max' => 'Description cannot exceed 1000 characters.',
            'is_paid.boolean' => 'Is paid must be a boolean.',
            'is_active.boolean' => 'Is active must be a boolean.',
        ];
    }
} 