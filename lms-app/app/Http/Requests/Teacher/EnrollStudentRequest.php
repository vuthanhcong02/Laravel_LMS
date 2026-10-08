<?php

namespace App\Http\Requests\Teacher;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EnrollStudentRequest extends FormRequest
{
    /**
     * Determine if the teacher is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === User::ROLE_TEACHER;
    }

    /**
     * Validation rules for enrolling students.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_ids' => ['required_without:user_id', 'array', 'min:1'],
            'user_ids.*' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('role', User::ROLE_STUDENT);
                }),
            ],
            'user_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('role', User::ROLE_STUDENT);
                }),
            ],
        ];
    }

    /**
     * Custom validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_ids.required_without' => __('Vui lòng chọn ít nhất một học viên cần thêm vào lớp.'),
            'user_ids.array'            => __('Danh sách học viên không đúng định dạng.'),
            'user_ids.min'              => __('Vui lòng chọn ít nhất một học viên cần thêm vào lớp.'),
            'user_ids.*.exists'         => __('Một hoặc nhiều học viên đã chọn không hợp lệ.'),
            'user_id.exists'            => __('Học viên đã chọn không hợp lệ hoặc không tồn tại.'),
        ];
    }
}
