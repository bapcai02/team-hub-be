<?php

namespace App\Interfaces\Http\Requests\Meeting;

use Illuminate\Foundation\Http\FormRequest;

class CreateMeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date|after:now',
            'location' => 'nullable|string|max:255',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'link' => 'nullable|url',
            'participant_ids' => 'nullable|array',
            'participant_ids.*' => 'exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Tiêu đề cuộc họp là bắt buộc',
            'start_time.required' => 'Thời gian bắt đầu là bắt buộc',
            'start_time.after' => 'Thời gian bắt đầu phải sau thời gian hiện tại',
            'duration_minutes.required' => 'Thời lượng cuộc họp là bắt buộc',
            'duration_minutes.min' => 'Thời lượng cuộc họp tối thiểu 15 phút',
            'duration_minutes.max' => 'Thời lượng cuộc họp tối đa 8 giờ',
            'link.url' => 'Link cuộc họp không hợp lệ',
        ];
    }
} 