<?php

namespace App\Services\Teacher;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class TeacherClassService
{
    /**
     * Get paginated courses for the authenticated teacher.
     */
    public function getTeacherClasses(): LengthAwarePaginator
    {
        return Course::where('teacher_id', Auth::id())
            ->withCount('enrollments')
            ->orderBy('created_at', 'desc')
            ->paginate(config('app.paginate_limit', 10));
    }

    /**
     * Get paginated and filtered students (enrollments) for a specific course.
     */
    public function getPaginatedStudents(Course $course, ?string $search = null): LengthAwarePaginator
    {
        return $course->enrollments()
            ->with('user')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(config('app.paginate_limit', 10));
    }
}
