<?php

namespace App\Http\Requests\Admin\Course;

use App\Models\CourseSchedule;
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
            
            // Schedule validation
            'start_date'   => 'nullable|required_with:start_time|date',
            'end_date'     => 'nullable|required_with:start_time|date|after_or_equal:start_date',
            'start_time'   => 'nullable|date_format:H:i',
            'end_time'     => 'nullable|required_with:start_time|date_format:H:i|after:start_time',
            'days_of_week' => 'nullable|required_with:start_time|array',
            'days_of_week.*' => 'integer|min:0|max:6',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->any()) {
                return;
            }

            $data = $validator->validated();

            if (empty($data['teacher_id']) || empty($data['start_date']) || empty($data['end_date']) || empty($data['start_time']) || empty($data['end_time']) || empty($data['days_of_week'])) {
                return;
            }

            $overlappingSchedule = CourseSchedule::with('course')
                ->whereIn('day_of_week', $data['days_of_week'])
                ->where(function ($q) use ($data) {
                    $q->where('start_time', '<', $data['end_time'])
                      ->where('end_time', '>', $data['start_time']);
                })
                ->whereHas('course', function ($q) use ($data) {
                    $q->where('teacher_id', $data['teacher_id'])
                      ->where('start_date', '<=', $data['end_date'])
                      ->where('end_date', '>=', $data['start_date']);
                })
                ->first();

            if ($overlappingSchedule) {
                $validator->errors()->add('start_time', __('Lịch học bị trùng với khóa: :course', ['course' => $overlappingSchedule->course->title]));
            }
        });
    }
}
