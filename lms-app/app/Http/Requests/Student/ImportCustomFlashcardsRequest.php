<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class ImportCustomFlashcardsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'cards' => ['required', 'array', 'min:1', 'max:500'],
            'cards.*.word' => ['required', 'string', 'max:100'],
            'cards.*.pinyin' => ['nullable', 'string', 'max:200'],
            'cards.*.meaning' => ['required', 'string', 'max:1000'],
            'cards.*.example' => ['nullable', 'string', 'max:1000'],
            'cards.*.example_meaning' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Custom validation attribute names.
     */
    public function attributes(): array
    {
        return [
            'cards' => __('danh sách từ vựng'),
            'cards.*.word' => __('chữ Hán'),
            'cards.*.pinyin' => __('phiên âm pinyin'),
            'cards.*.meaning' => __('ý nghĩa tiếng Việt'),
            'cards.*.example' => __('câu ví dụ'),
            'cards.*.example_meaning' => __('dịch câu ví dụ'),
        ];
    }
}
