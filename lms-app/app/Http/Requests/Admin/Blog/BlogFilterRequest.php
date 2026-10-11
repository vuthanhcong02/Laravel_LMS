<?php

namespace App\Http\Requests\Admin\Blog;

use App\Models\Blog;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class BlogFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare data before validation to prevent redirect loops.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('category_id')) {
            $catId = $this->category_id;
            if (! is_numeric($catId) || ! Category::where('id', (int) $catId)->exists()) {
                $this->merge(['category_id' => null]);
            }
        }

        if ($this->has('status') && ! in_array((int) $this->status, [Blog::STATUS_DRAFT, Blog::STATUS_PUBLISHED], true)) {
            $this->merge(['status' => null]);
        }
    }

    public function rules(): array
    {
        return [
            'search'      => 'nullable|string|max:255',
            'status'      => 'nullable|in:' . Blog::STATUS_DRAFT . ',' . Blog::STATUS_PUBLISHED,
            'category_id' => 'nullable|integer',
        ];
    }
}
