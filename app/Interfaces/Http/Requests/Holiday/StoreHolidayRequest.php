<?php

namespace App\Interfaces\Http\Requests\Holiday;

use Illuminate\Foundation\Http\FormRequest;

class StoreHolidayRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:national,company,regional',
            'description' => 'nullable|string|max:1000',
            'is_paid' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Holiday name is required.',
            'name.string' => 'Holiday name must be a string.',
            'name.max' => 'Holiday name cannot exceed 255 characters.',
            'date.required' => 'Holiday date is required.',
            'date.date' => 'Holiday date must be a valid date.',
            'type.required' => 'Holiday type is required.',
            'type.in' => 'Invalid holiday type.',
            'description.string' => 'Description must be a string.',
            'description.max' => 'Description cannot exceed 1000 characters.',
            'is_paid.boolean' => 'Is paid must be a boolean.',
            'is_active.boolean' => 'Is active must be a boolean.',
        ];
    }
} 