<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SearchStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === User::ROLE_TEACHER;
    }

    /**
     * Prepare data before validation.
     * Safely sanitize 'tab' parameter on GET requests to prevent infinite redirect loops.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('tab') && ! in_array($this->tab, ['overview', 'lessons', 'students'], true)) {
            $this->merge(['tab' => 'overview']);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:255',
            'tab'    => 'nullable|string|in:overview,lessons,students',
            'page'   => 'nullable|integer|min:1',
        ];
    }
}
