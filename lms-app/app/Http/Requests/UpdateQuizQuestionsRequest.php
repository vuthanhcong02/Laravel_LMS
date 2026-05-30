<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\QuestionType;
use App\Models\User;

/**
 * Request validation for bulk updating quiz questions and options
 */
class UpdateQuizQuestionsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Ownership check is handled in the controller via QuizPolicy
        return in_array($this->user()->role, [User::ROLE_ADMIN, User::ROLE_TEACHER]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $quiz = $this->route('quiz');

        return [
            'questions' => 'nullable|array',
            'questions.*.id' => [
                'nullable',
                'integer',
                Rule::exists('questions', 'id')->where('quiz_id', $quiz ? $quiz->id : null),
            ],
            'questions.*.type' => ['required', Rule::enum(QuestionType::class)],
            'questions.*.question_text' => 'required|string',
            'questions.*.marks' => 'required|numeric|min:0.5|max:100',
            'questions.*.image' => 'nullable|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'questions.*.audio' => 'nullable|mimes:mp3,wav,ogg|max:5120',
            'questions.*.options' => 'required_unless:questions.*.type,essay|array',
            'questions.*.options.*.id' => 'nullable|integer|exists:options,id',
            'questions.*.options.*.option_text' => 'required_with:questions.*.options|string',
            'questions.*.options.*.is_correct' => 'nullable|boolean',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'questions.*.question_text' => __('nội dung câu hỏi'),
            'questions.*.type' => __('loại câu hỏi'),
            'questions.*.options.*.option_text' => __('nội dung đáp án'),
        ];
    }
}
