<?php

namespace App\Http\Requests\Admin\Course;

use App\Models\CourseSchedule;
use Illuminate\Foundation\Http\FormRequest;

class CourseUpdateRequest extends FormRequest
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
            
            // Schedule validation (date range & weekly recurring time slots)
            'start_date'   => 'nullable|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
            'start_time'   => 'nullable|required_with:end_time,days_of_week|date_format:H:i',
            'end_time'     => 'nullable|required_with:start_time,days_of_week|date_format:H:i|after:start_time',
            'days_of_week' => 'nullable|required_with:start_time,end_time|array',
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

            // Skip schedule collision checks if required fields are missing
            if (empty($data['teacher_id']) || empty($data['start_time']) || empty($data['end_time']) || empty($data['days_of_week'])) {
                return;
            }

            $courseRoute = $this->route('course');
            $currentCourseId = is_object($courseRoute) ? $courseRoute->id : $courseRoute;

            $overlappingSchedule = CourseSchedule::with('course.teacher')
                ->where('course_id', '!=', $currentCourseId)
                ->whereIn('day_of_week', $data['days_of_week'])
                ->where(function ($q) use ($data) {
                    $q->where('start_time', '<', $data['end_time'])
                      ->where('end_time', '>', $data['start_time']);
                })
                ->whereHas('course', function ($q) use ($data, $currentCourseId) {
                    $q->where('id', '!=', $currentCourseId)
                      ->where('teacher_id', $data['teacher_id'])
                      ->where(function ($dateQ) use ($data) {
                          // Check date range overlap only if dates are defined
                          if (!empty($data['start_date']) && !empty($data['end_date'])) {
                              $dateQ->where(function ($sub) use ($data) {
                                  $sub->whereNull('start_date')
                                      ->orWhereNull('end_date')
                                      ->orWhere(function ($overlap) use ($data) {
                                          $overlap->where('start_date', '<=', $data['end_date'])
                                                  ->where('end_date', '>=', $data['start_date']);
                                      });
                              });
                          }
                      });
                })
                ->first();

            if ($overlappingSchedule) {
                $validator->errors()->add('start_time', __('Lịch học bị trùng với khóa: :course (Giáo viên đã có lịch dạy vào khung giờ này)', [
                    'course' => $overlappingSchedule->course->title ?? 'N/A'
                ]));
            }
        });
    }
}
