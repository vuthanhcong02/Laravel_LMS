<?php

namespace App\Http\Requests\Admin\Course;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class CourseFilterRequest extends FormRequest
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

        if ($this->has('status') && ! in_array((string) $this->status, ['0', '1'], true)) {
            $this->merge(['status' => null]);
        }
    }

    public function rules(): array
    {
        return [
            'search'      => 'nullable|string|max:255',
            'status'      => 'nullable|in:0,1',
            'category_id' => 'nullable|integer',
        ];
    }
}
