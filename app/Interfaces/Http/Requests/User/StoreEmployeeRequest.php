<?php

namespace App\Interfaces\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
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
        return [
            'user_id' => 'required|exists:users,id|unique:employees,user_id',
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:100',
            'salary' => 'required|numeric|min:0',
            'contract_type' => 'required|in:full-time,part-time,intern',
            'hire_date' => 'required|date|before_or_equal:today',
            'dob' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'User ID là bắt buộc.',
            'user_id.exists' => 'User không tồn tại.',
            'user_id.unique' => 'User đã có employee record.',
            'department_id.required' => 'Phòng ban là bắt buộc.',
            'department_id.exists' => 'Phòng ban không tồn tại.',
            'position.required' => 'Chức vụ là bắt buộc.',
            'salary.required' => 'Lương là bắt buộc.',
            'salary.numeric' => 'Lương phải là số.',
            'salary.min' => 'Lương phải lớn hơn 0.',
            'contract_type.required' => 'Loại hợp đồng là bắt buộc.',
            'contract_type.in' => 'Loại hợp đồng không hợp lệ.',
            'hire_date.required' => 'Ngày thuê là bắt buộc.',
            'hire_date.before_or_equal' => 'Ngày thuê không thể sau ngày hiện tại.',
            'dob.required' => 'Ngày sinh là bắt buộc.',
            'dob.before' => 'Ngày sinh phải trước ngày hiện tại.',
            'gender.required' => 'Giới tính là bắt buộc.',
            'gender.in' => 'Giới tính không hợp lệ.',
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'address.required' => 'Địa chỉ là bắt buộc.',
        ];
    }
} 