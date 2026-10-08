<?php

namespace App\Services\Teacher;

use App\Enums\EnrollmentStatus;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class TeacherClassService
{
    /**
     * Get paginated list of classes assigned to the authenticated teacher.
     */
    public function getTeacherClasses(): LengthAwarePaginator
    {
        return Course::where('teacher_id', Auth::id())
            ->withCount('enrollments')
            ->orderBy('created_at', 'desc')
            ->paginate(config('app.paginate_limit', 10));
    }

    /**
     * Get paginated & searchable list of enrolled students in a specific course.
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

    /**
     * Get real-time class learning progress statistics.
     *
     * @return array{total_students: int, completed_students: int, completion_rate: int}
     */
    public function getClassStats(Course $course): array
    {
        $totalStudents = $course->enrollments()->count();
        $completedStudents = $course->enrollments()
            ->where('status', EnrollmentStatus::COMPLETED)
            ->count();

        $completionRate = $totalStudents > 0 
            ? (int) round(($completedStudents / $totalStudents) * 100) 
            : 0;

        return [
            'total_students'     => $totalStudents,
            'completed_students' => $completedStudents,
            'completion_rate'    => $completionRate,
        ];
    }

    /**
     * Search available students who are not yet enrolled in this course.
     */
    public function getAvailableStudents(Course $course, ?string $search = null, int $limit = 20): Collection
    {
        // Enrolled student IDs in this course
        $enrolledUserIds = $course->enrollments()->pluck('user_id')->toArray();

        return User::query()
            ->where('role', User::ROLE_STUDENT)
            ->whereNotIn('id', $enrolledUserIds)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('first_name', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Enroll a student into a course.
     */
    public function enrollStudent(Course $course, int $userId): Enrollment
    {
        return Enrollment::updateOrCreate(
            [
                'course_id' => $course->id,
                'user_id'   => $userId,
            ],
            [
                'status'     => EnrollmentStatus::ACTIVE,
                'created_at' => now(),
            ]
        );
    }

    /**
     * Bulk enroll multiple students into a course.
     *
     * @param array<int> $userIds
     * @return int Number of successfully enrolled students.
     */
    public function enrollStudents(Course $course, array $userIds): int
    {
        $count = 0;
        foreach (array_unique($userIds) as $userId) {
            if ($userId > 0) {
                $this->enrollStudent($course, (int) $userId);
                $count++;
            }
        }

        return $count;
    }

    /**
     * Remove (unenroll) a student from a course.
     */
    public function removeStudent(Course $course, int $enrollmentId): bool
    {
        $enrollment = $course->enrollments()->where('id', $enrollmentId)->first();

        if ($enrollment) {
            return (bool) $enrollment->delete();
        }

        return false;
    }
}
