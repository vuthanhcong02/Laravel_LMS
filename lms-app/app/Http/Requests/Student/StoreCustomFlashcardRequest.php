<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomFlashcardRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'word' => 'required|string|max:100',
            'pinyin' => 'required|string|max:200',
            'meaning' => 'required|string|max:1000',
            'example' => 'nullable|string|max:1000',
            'example_meaning' => 'nullable|string|max:1000',
        ];
    }
}
