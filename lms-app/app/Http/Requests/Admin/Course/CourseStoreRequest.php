<?php

namespace App\Http\Requests\Admin\Course;

use Illuminate\Foundation\Http\FormRequest;

class CourseStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'category_id'  => 'nullable|integer|exists:categories,id',
            'teacher_id'   => 'nullable|integer|exists:users,id',
            'price'        => 'nullable|numeric|min:0',
            'is_published' => 'required|boolean',
            'thumbnail'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ];
    }
}
