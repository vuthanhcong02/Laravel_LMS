<?php

namespace App\Http\Requests\Admin\Blog;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Blog;

class BlogFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|in:' . Blog::STATUS_DRAFT . ',' . Blog::STATUS_PUBLISHED,
            'category_id' => 'nullable|integer|exists:categories,id',
        ];
    }
}
