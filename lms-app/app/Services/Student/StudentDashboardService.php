<?php

namespace App\Services\Student;

use App\Models\Enrollment;
use App\Enums\EnrollmentStatus;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\QuizAttempt;
use App\Models\Quiz;
use App\Models\CourseSchedule;
use Carbon\Carbon;

class StudentDashboardService
{
    /**
     * Get general summary statistics for the student.
     */
    public function getSummaryStats(int $userId): array
    {
        // 1. Number of active courses (active enrollments)
        $activeCoursesCount = Enrollment::where('user_id', $userId)
            ->where('status', EnrollmentStatus::ACTIVE)
            ->count();

        // 2. Number of completed or submitted assignments
        $completedAssignmentsCount = AssignmentSubmission::where('user_id', $userId)
            ->whereIn('status', [
                AssignmentSubmission::STATUS_SUBMITTED,
                AssignmentSubmission::STATUS_GRADED
            ])
            ->count();

        // 3. Number of completed quizzes
        $completedQuizzesCount = QuizAttempt::where('user_id', $userId)
            ->distinct('quiz_id')
            ->count();

        return [
            'active_courses' => $activeCoursesCount,
            'completed_assignments' => $completedAssignmentsCount,
            'completed_quizzes' => $completedQuizzesCount
        ];
    }

    /**
     * Get continuing courses for the "Continue Learning" section.
     */
    public function getContinuingCourses(int $userId): array
    {
        $enrollments = Enrollment::where('user_id', $userId)
            ->where('status', EnrollmentStatus::ACTIVE)
            ->with(['course.lessons', 'course.category'])
            ->latest()
            ->get();

        $continuingCourses = [];

        foreach ($enrollments as $enrollment) {
            $course = $enrollment->course;
            if (!$course) continue;

            $lessons = $course->lessons;
            $lessonsCount = $lessons->count();

            // Skip if the course has no lessons
            if ($lessonsCount === 0) {
                continue;
            }

            // Mock progress based on enrollment ID to create realistic variance per student
            $completedCount = ($enrollment->id * 3) % ($lessonsCount + 1);
            if ($completedCount === 0) {
                $completedCount = min(1, $lessonsCount); // Always ensure at least 1 completed lesson for visual balance
            }
            
            $progressPercentage = round(($completedCount / $lessonsCount) * 100);

            // Find the next lesson to learn
            $nextLessonIndex = min($completedCount, $lessonsCount - 1);
            $nextLesson = $lessons->get($nextLessonIndex);
            $nextLessonTitle = $nextLesson ? $nextLesson->title : __('Bài giảng tiếp theo');

            $continuingCourses[] = [
                'id' => $course->id,
                'title' => $course->title,
                'thumbnail' => $course->thumbnail ?? 'https://images.unsplash.com/photo-1546410531-bb4caa6b424d?q=80&w=640',
                'category_name' => $course->category ? $course->category->name : __('Tổng hợp'),
                'lessons_count' => $lessonsCount,
                'completed_lessons' => $completedCount,
                'progress_percentage' => $progressPercentage,
                'next_lesson_title' => $nextLessonTitle,
                'next_lesson_id' => $nextLesson ? $nextLesson->id : null
            ];
        }

        return $continuingCourses;
    }

    /**
     * Get upcoming schedules for the student.
     */
    public function getUpcomingSchedules(int $userId, int $limit = 3): array
    {
        $enrolledCourseIds = Enrollment::where('user_id', $userId)
            ->where('status', EnrollmentStatus::ACTIVE)
            ->pluck('course_id');

        if ($enrolledCourseIds->isEmpty()) {
            return [];
        }

        // Fetch all weekly schedules for enrolled courses along with teacher info
        $schedules = CourseSchedule::whereIn('course_id', $enrolledCourseIds)
            ->with(['course.teacher'])
            ->get();

        $now = Carbon::now();
        $formattedSchedules = [];

        foreach ($schedules as $schedule) {
            $scheduleDay = $schedule->day_of_week; // 0: Sunday, 1: Monday, ..., 6: Saturday
            
            // Calculate day difference to reach the next schedule date
            $diff = $scheduleDay - $now->dayOfWeek;
            
            if ($diff < 0) {
                $diff += 7; // Postpone to next week
            } elseif ($diff === 0) {
                // If it is today, check if the session has already ended
                if ($now->format('H:i:s') > $schedule->end_time) {
                    $diff = 7; // Postpone to next week
                }
            }
            
            $targetDate = $now->copy()->addDays($diff);
            $teacher = $schedule->course ? $schedule->course->teacher : null;
            
            $formattedSchedules[] = [
                'course_title' => $schedule->course ? $schedule->course->title : __('Khóa học'),
                'day_of_week' => $this->getVietnameseDayOfWeek($targetDate->dayOfWeek),
                'day_name_full' => $this->getVietnameseDayOfWeekFull($targetDate->dayOfWeek),
                'day_number' => $targetDate->format('d'),
                'formatted_date' => $targetDate->translatedFormat('d/m/Y'),
                'start_time' => Carbon::parse($schedule->start_time)->format('H:i'),
                'end_time' => Carbon::parse($schedule->end_time)->format('H:i'),
                'room' => $schedule->room ?? __('Phòng học trực tuyến'),
                'is_today' => $targetDate->isToday(),
                'target_date_string' => $targetDate->toDateString(),
                'teacher_name' => $teacher ? "{$teacher->first_name} {$teacher->last_name}" : __('Chưa phân công'),
                'teacher_avatar' => $teacher ? $teacher->avatar_url : null,
                'meeting_link' => $schedule->room ? null : 'https://zoom.us/j/99988877766' // Mock Zoom link if online
            ];
        }

        // Sort schedules by the closest upcoming date
        usort($formattedSchedules, function ($a, $b) {
            if ($a['target_date_string'] === $b['target_date_string']) {
                return strcmp($a['start_time'], $b['start_time']);
            }
            return strcmp($a['target_date_string'], $b['target_date_string']);
        });

        return array_slice($formattedSchedules, 0, $limit);
    }

    /**
     * Get to-do tasks for the student (pending assignments & unattempted quizzes).
     */
    public function getTodoTasks(int $userId): array
    {
        $enrolledCourseIds = Enrollment::where('user_id', $userId)
            ->where('status', EnrollmentStatus::ACTIVE)
            ->pluck('course_id');

        if ($enrolledCourseIds->isEmpty()) {
            return [];
        }

        $tasks = [];

        // 1. Fetch pending assignments
        $submittedAssignmentIds = AssignmentSubmission::where('user_id', $userId)
            ->pluck('assignment_id');

        $pendingAssignments = Assignment::whereIn('course_id', $enrolledCourseIds)
            ->where('status', Assignment::STATUS_PUBLISHED)
            ->whereNotIn('id', $submittedAssignmentIds)
            ->orderBy('due_date')
            ->limit(3)
            ->get();

        foreach ($pendingAssignments as $assignment) {
            $dueDate = $assignment->due_date ? Carbon::parse($assignment->due_date) : null;
            
            if ($dueDate) {
                if ($dueDate->isToday()) {
                    $dueString = __('Hạn chót: Hôm nay');
                    $isOverdue = false;
                } elseif ($dueDate->isPast()) {
                    $dueString = __('Đã trễ hạn nộp');
                    $isOverdue = true;
                } else {
                    $dueString = __('Hạn chót: :days ngày tới', ['days' => $dueDate->diffInDays(Carbon::now()) + 1]);
                    $isOverdue = false;
                }
            } else {
                $dueString = __('Không có hạn nộp');
                $isOverdue = false;
            }

            $tasks[] = [
                'type' => 'assignment',
                'title' => $assignment->title,
                'due_info' => $dueString,
                'is_urgent' => $isOverdue || ($dueDate && $dueDate->isToday()),
                'link' => route('student.assignments.index')
            ];
        }

        // 2. Fetch unattempted quizzes
        $attemptedQuizIds = QuizAttempt::where('user_id', $userId)
            ->pluck('quiz_id');

        $pendingQuizzes = Quiz::whereIn('course_id', $enrolledCourseIds)
            ->whereNotIn('id', $attemptedQuizIds)
            ->limit(2)
            ->get();

        foreach ($pendingQuizzes as $quiz) {
            $tasks[] = [
                'type' => 'quiz',
                'title' => $quiz->title,
                'due_info' => __('Chưa thực hiện'),
                'is_urgent' => false,
                'link' => '#' // Placeholder link until quiz interface is built
            ];
        }

        return $tasks;
    }

    /**
     * Convert day of week to Vietnamese abbreviation.
     */
    private function getVietnameseDayOfWeek(int $dayOfWeek): string
    {
        return match ($dayOfWeek) {
            Carbon::MONDAY => 'Th2',
            Carbon::TUESDAY => 'Th3',
            Carbon::WEDNESDAY => 'Th4',
            Carbon::THURSDAY => 'Th5',
            Carbon::FRIDAY => 'Th6',
            Carbon::SATURDAY => 'Th7',
            Carbon::SUNDAY => 'CN',
            default => 'Th2'
        };
    }

    /**
     * Convert day of week to full Vietnamese format.
     */
    private function getVietnameseDayOfWeekFull(int $dayOfWeek): string
    {
        return match ($dayOfWeek) {
            Carbon::MONDAY => 'Thứ Hai',
            Carbon::TUESDAY => 'Thứ Ba',
            Carbon::WEDNESDAY => 'Thứ Tư',
            Carbon::THURSDAY => 'Thứ Năm',
            Carbon::FRIDAY => 'Thứ Sáu',
            Carbon::SATURDAY => 'Thứ Bảy',
            Carbon::SUNDAY => 'Chủ Nhật',
            default => 'Thứ Hai'
        };
    }
}
