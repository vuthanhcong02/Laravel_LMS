<?php

namespace App\Services\Teacher;

use App\Models\User;
use App\Models\Course;
use App\Models\CourseSchedule;
use App\Models\AssignmentSubmission;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

/**
 * Service to handle data queries for the Teacher Dashboard
 */
class TeacherDashboardService
{
    /**
     * Get statistics summary for the teacher.
     *
     * @param int $teacherId
     * @return array
     */
    public function getSummaryStats(int $teacherId): array
    {
        // 1. Total unique students enrolled in any of this teacher's courses
        $totalStudents = User::where('role', User::ROLE_STUDENT)
            ->whereHas('enrollments', function ($q) use ($teacherId) {
                $q->whereHas('course', function ($q2) use ($teacherId) {
                    $q2->where('teacher_id', $teacherId);
                });
            })->count();

        // 2. Total courses owned by this teacher
        $totalCourses = Course::where('teacher_id', $teacherId)->count();

        // 3. Total submissions that are submitted and pending grading
        $pendingAssignmentsCount = AssignmentSubmission::where('status', AssignmentSubmission::STATUS_SUBMITTED)
            ->whereHas('assignment', function ($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId);
            })->count();

        return [
            'total_students'             => $totalStudents,
            'total_courses'              => $totalCourses,
            'pending_assignments_count'  => $pendingAssignmentsCount,
        ];
    }

    /**
     * Get teaching schedules for today.
     *
     * @param int $teacherId
     * @return Collection
     */
    public function getTodaySchedules(int $teacherId): Collection
    {
        $todayDayOfWeek = Carbon::now()->dayOfWeek; // 0 (Sunday) to 6 (Saturday)

        return CourseSchedule::with(['course'])
            ->where('day_of_week', $todayDayOfWeek)
            ->whereHas('course', function ($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId);
            })
            ->orderBy('start_time', 'asc')
            ->get()
            ->map(function ($schedule) {
                // Attach total students count
                $schedule->students_count = $schedule->course->enrollments()->count();

                // Attach current or first lesson title as placeholder/model info
                $firstLesson = $schedule->course->lessons()->orderBy('order', 'asc')->first();
                $schedule->current_lesson_title = $firstLesson 
                    ? $firstLesson->title 
                    : __('Chưa có bài học');

                return $schedule;
            });
    }

    /**
     * Get recent notifications for the logged in teacher.
     *
     * @param User $user
     * @param int $limit
     * @return Collection
     */
    public function getRecentNotifications(User $user, int $limit = 5): Collection
    {
        return $user->notifications()
            ->latest()
            ->take($limit)
            ->get();
    }
}
