<?php

namespace App\Http\Requests\Teacher;

use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;

class ReportIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare data before validation.
     * Safely sanitize 'course_id' to prevent redirect loops when filtering with stale or invalid IDs.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('course_id')) {
            $courseId = $this->course_id;
            if (! is_numeric($courseId) || ! Course::where('id', (int) $courseId)->exists()) {
                $this->merge(['course_id' => null]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'search'    => 'nullable|string|max:255',
            'course_id' => 'nullable|integer',
        ];
    }
}
