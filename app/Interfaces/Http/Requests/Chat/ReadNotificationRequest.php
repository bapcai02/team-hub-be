<?php

namespace App\Interfaces\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;

class ReadNotificationRequest extends FormRequest
{
    public function authorize()
    {
        // Allow all authenticated users to mark notifications as read
        return true;
    }

    public function rules()
    {
        return [
            // No additional fields required for marking as read
        ];
    }
} 