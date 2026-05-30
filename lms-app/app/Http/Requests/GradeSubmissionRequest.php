<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class GradeSubmissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === User::ROLE_TEACHER;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'score'            => 'required|numeric|min:0|max:10',
            'teacher_feedback' => 'nullable|string|max:2000',
            'audio_feedback'   => 'nullable|file|mimes:webm,mp3,wav,ogg,m4a|max:10240',
            'delete_audio'     => 'nullable|boolean',
        ];
    }
}
