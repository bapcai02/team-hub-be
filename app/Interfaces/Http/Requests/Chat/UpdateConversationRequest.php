<?php

namespace App\Interfaces\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConversationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'nullable|string',
            'add_member_ids' => 'array',
            'add_member_ids.*' => 'integer|distinct|not_in:' . $this->user()->id,
            'remove_member_ids' => 'array',
            'remove_member_ids.*' => 'integer|distinct|not_in:' . $this->user()->id,
        ];
    }
} 