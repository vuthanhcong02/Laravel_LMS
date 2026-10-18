<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreHskMockExamRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'data_file' => 'required|file|mimes:json,txt,csv|max:10240',
            'media_files' => 'nullable|array',
            'media_files.*' => 'nullable|file|mimes:jpeg,png,jpg,webp,mp3,wav,ogg,m4a|max:102400'
        ];
    }
}
