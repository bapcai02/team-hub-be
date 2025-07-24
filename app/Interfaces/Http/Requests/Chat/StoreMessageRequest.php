<?php

namespace App\Interfaces\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Có thể bổ sung logic phân quyền nếu cần
    }

    public function rules()
    {
        return [
            'type' => 'required|in:text,file,image,video,meeting',
            'content' => 'required|string',
        ];
    }
} 