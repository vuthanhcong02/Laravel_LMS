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
            'data_file' => 'required|file|mimes:json,txt,csv',
            'media_files' => 'required|array',
            'media_files.*' => 'required|file|mimes:jpeg,png,jpg,mp3,wav'
        ];
    }
}
