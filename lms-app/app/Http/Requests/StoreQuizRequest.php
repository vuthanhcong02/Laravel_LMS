<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\QuizType;
use App\Models\User;

/**
 * Request validation for creating/updating a Quiz
 */
class StoreQuizRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only teachers and admins can manage quizzes
        return in_array($this->user()->role, [User::ROLE_ADMIN, User::ROLE_TEACHER]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $courseRule = Rule::exists('courses', 'id');

        // If teacher, only allow creating/updating quizzes for courses they own (prevent IDOR)
        if ($this->user()->role === User::ROLE_TEACHER) {
            $courseRule->where('teacher_id', $this->user()->id);
        }

        return [
            'course_id' => ['required', $courseRule],
            'title' => 'required|string|max:255',
            'type' => ['required', Rule::enum(QuizType::class)],
            'time_limit' => 'required|integer|min:0|max:1440', // 0 = no time limit, max = 24h
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
            'course_id' => __('khóa học'),
            'title' => __('tiêu đề'),
            'type' => __('loại bài thi'),
            'time_limit' => __('thời gian làm bài'),
        ];
    }
}
