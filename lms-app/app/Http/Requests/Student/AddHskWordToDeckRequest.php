<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class AddHskWordToDeckRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'hsk_vocabulary_id' => 'nullable|integer|exists:hsk_vocabularies,id',
            'word' => 'required_without:hsk_vocabulary_id|string|max:255',
            'pinyin' => 'nullable|string|max:255',
            'meaning' => 'nullable|string',
            'example' => 'nullable|string',
            'example_meaning' => 'nullable|string',
        ];
    }
}
