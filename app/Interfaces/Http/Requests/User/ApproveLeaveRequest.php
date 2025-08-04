<?php

namespace App\Interfaces\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ApproveLeaveRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'status' => 'required|in:approved,rejected',
            'note' => 'nullable|string|max:1000',
        ];
    }
} 