<?php

namespace App\Interfaces\Http\Requests\Meeting;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'sometimes|required|date',
            'duration_minutes' => 'sometimes|required|integer|min:15|max:480',
            'link' => 'nullable|url',
            'status' => 'sometimes|required|in:scheduled,ongoing,finished,cancelled',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Tiêu đề cuộc họp là bắt buộc',
            'start_time.required' => 'Thời gian bắt đầu là bắt buộc',
            'duration_minutes.required' => 'Thời lượng cuộc họp là bắt buộc',
            'duration_minutes.min' => 'Thời lượng cuộc họp tối thiểu 15 phút',
            'duration_minutes.max' => 'Thời lượng cuộc họp tối đa 8 giờ',
            'link.url' => 'Link cuộc họp không hợp lệ',
            'status.required' => 'Trạng thái cuộc họp là bắt buộc',
            'status.in' => 'Trạng thái cuộc họp không hợp lệ',
        ];
    }
} 