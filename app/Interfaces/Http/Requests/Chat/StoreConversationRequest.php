<?php

namespace App\Interfaces\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;

class StoreConversationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type' => 'required|in:personal,group',
            'name' => 'nullable|string',
            'member_ids' => 'required|array|min:1',
            'member_ids.*' => 'integer|distinct|not_in:' . $this->user()->id,
        ];
    }
} 