<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $email = $this->input('email');
        $name = $this->input('name');
        $message = $this->input('message');
        $topics = $this->input('topics');

        if (empty($name) && !empty($email)) {
            $emailPrefix = explode('@', $email)[0];
            $name = 'Khách hàng (' . $emailPrefix . ')';
        }

        if (empty($message)) {
            $message = 'Đăng ký nhận tư vấn lộ trình học tiếng Trung miễn phí.';
        }

        if (empty($topics)) {
            $topics = ['Tư vấn khóa học'];
        }

        $this->merge([
            'name' => $name,
            'message' => $message,
            'topics' => $topics,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|min:2',
            'email' => 'required|email|regex:/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/|max:255',
            'phone' => ['nullable', 'string', 'regex:/(84|0[3|5|7|8|9])+([0-9]{8})\b/'],
            'topics' => 'nullable|array',
            'message' => 'required|string|min:10|max:10000',
            'website' => 'nullable|max:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'name.min' => 'Họ và tên phải có ít nhất 2 ký tự.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.regex' => 'Email không hợp lệ (cần có đuôi tên miền hợp lệ).',
            'phone.regex' => 'Số điện thoại không hợp lệ (Vui lòng nhập SĐT Việt Nam hợp lệ).',
            'message.required' => 'Vui lòng nhập nội dung lời nhắn.',
            'message.min' => 'Nội dung lời nhắn phải có ít nhất 10 ký tự.',
            'website.max' => 'Phát hiện hành vi nghi vấn spam bot.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson() || $this->ajax() || $this->wantsJson()) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422));
        }

        parent::failedValidation($validator);
    }
}
