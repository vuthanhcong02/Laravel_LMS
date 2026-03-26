<?php

namespace App\Http\Requests\Admin\Course;

use Illuminate\Foundation\Http\FormRequest;

class CourseFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search'      => 'nullable|string|max:255',
            'status'      => 'nullable|in:0,1',
            'category_id' => 'nullable|integer|exists:categories,id',
        ];
    }
}
