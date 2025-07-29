<?php

namespace App\Interfaces\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $employeeId = $this->route('id');
        
        return [
            'user_id' => [
                'sometimes',
                'exists:users,id',
                Rule::unique('employees', 'user_id')->ignore($employeeId),
            ],
            'department_id' => 'sometimes|exists:departments,id',
            'position' => 'sometimes|string|max:100',
            'salary' => 'sometimes|numeric|min:0',
            'contract_type' => 'sometimes|in:full-time,part-time,intern',
            'hire_date' => 'sometimes|date|before_or_equal:today',
            'dob' => 'sometimes|date|before:today',
            'gender' => 'sometimes|in:male,female,other',
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'user_id.exists' => 'User không tồn tại.',
            'user_id.unique' => 'User đã có employee record.',
            'department_id.exists' => 'Phòng ban không tồn tại.',
            'position.string' => 'Chức vụ phải là chuỗi.',
            'position.max' => 'Chức vụ không được quá 100 ký tự.',
            'salary.numeric' => 'Lương phải là số.',
            'salary.min' => 'Lương phải lớn hơn 0.',
            'contract_type.in' => 'Loại hợp đồng không hợp lệ.',
            'hire_date.date' => 'Ngày thuê không đúng định dạng.',
            'hire_date.before_or_equal' => 'Ngày thuê không thể sau ngày hiện tại.',
            'dob.date' => 'Ngày sinh không đúng định dạng.',
            'dob.before' => 'Ngày sinh phải trước ngày hiện tại.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'phone.string' => 'Số điện thoại phải là chuỗi.',
            'phone.max' => 'Số điện thoại không được quá 20 ký tự.',
            'address.string' => 'Địa chỉ phải là chuỗi.',
            'address.max' => 'Địa chỉ không được quá 255 ký tự.',
        ];
    }
} 