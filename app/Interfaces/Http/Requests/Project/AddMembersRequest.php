<?php

namespace App\Interfaces\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class AddMembersRequest extends FormRequest
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
            'role' => 'sometimes|string|in:owner,manager,member,viewer',
        ];
    }
} 