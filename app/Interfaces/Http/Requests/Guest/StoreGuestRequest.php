<?php

namespace App\Interfaces\Http\Requests\Guest;

use Illuminate\Foundation\Http\FormRequest;

class StoreGuestRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'type' => 'required|in:guest,partner,vendor,client',
            'status' => 'sometimes|in:active,inactive,blocked',
            'notes' => 'nullable|string',
            'avatar' => 'nullable|string',
            'first_visit_date' => 'nullable|date',
            'last_visit_date' => 'nullable|date',
        ];
    }
} 