<?php

namespace App\Interfaces\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class RemoveMembersRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'member_ids' => 'required|array|min:1',
            'member_ids.*' => 'integer|exists:users,id',
        ];
    }
} 