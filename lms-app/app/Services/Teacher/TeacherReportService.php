<?php

namespace App\Services\Teacher;

use App\Models\AssignmentSubmission;
use App\Models\User;
use App\Models\Course;
use App\Models\QuizAttempt;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class TeacherReportService
{
    /**
     * Get paginated students that belong to any of the teacher's courses
     */
    public function getStudents(string $search = null, ?int $courseId = null): LengthAwarePaginator
    {
        $teacherId = Auth::id();

        // Get students enrolled in courses taught by this teacher
        $query = User::where('role', User::ROLE_STUDENT)
            ->whereHas('enrollments', function (Builder $q) use ($teacherId, $courseId) {
                $q->whereHas('course', function (Builder $q2) use ($teacherId, $courseId) {
                    $q2->where('teacher_id', $teacherId);
                    if ($courseId) {
                        $q2->where('id', $courseId);
                    }
                });
            });

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'desc')->paginate(config('app.paginate_limit', 10));
    }

    /**
     * Get detailed report for a single student.
     * Ensure the student actually belongs to the teacher's class (security check IDOR).
     */
    public function getStudentReport(User $student)
    {
        $teacherId = Auth::id();

        // Check if student is enrolled in ANY of the teacher's courses
        $belongsToTeacher = $student->enrollments()->whereHas('course', function ($q) use ($teacherId) {
            $q->where('teacher_id', $teacherId);
        })->exists();

        if (!$belongsToTeacher) {
            abort(403, __('Bạn không có quyền xem báo cáo của sinh viên này.'));
        }

        return $student;
    }

    /**
     * Get real evaluation history (Assignments and Quizzes) and calculate stats
     */
    public function getStudentEvaluationHistory(User $student)
    {
        $teacherId = Auth::id();

        // 1. Get assignments
        $assignmentSubmissions = AssignmentSubmission::with(['assignment.course'])
            ->where('user_id', $student->id)
            ->whereHas('assignment', function($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId);
            })
            ->get()
            ->map(function ($submission) {
                $status = $submission->status == AssignmentSubmission::STATUS_GRADED ? 'Đã chấm' : 'Đang chờ';
                $color = $status == 'Đã chấm' ? 'emerald' : 'amber';

                return [
                    'id' => 'a_'.$submission->id,
                    'type' => 'Bài tập',
                    'icon' => 'assignment',
                    'color' => $color,
                    'title' => $submission->assignment->title,
                    'course' => $submission->assignment->course->title,
                    'score' => $submission->score,
                    'status' => $status,
                    'date' => $submission->created_at->format('d/m/Y H:i'),
                    'timestamp' => $submission->created_at->timestamp,
                ];
            });

        // 2. Get quizzes
        $quizAttempts = QuizAttempt::with(['quiz.course'])
            ->where('user_id', $student->id)
            ->whereHas('quiz.course', function($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId);
            })
            ->get()
            ->map(function ($attempt) {
                $status = 'Đã chấm';
                $color = 'orange';

                return [
                    'id' => 'q_'.$attempt->id,
                    'type' => 'Bài kiểm tra',
                    'icon' => 'quiz',
                    'color' => $color,
                    'title' => $attempt->quiz->title,
                    'course' => $attempt->quiz->course->title,
                    'score' => $attempt->score,
                    'status' => $status,
                    'date' => $attempt->created_at->format('d/m/Y H:i'),
                    'timestamp' => $attempt->created_at->timestamp,
                ];
            });

        // 3. Merge and sort desc
        $histories = $assignmentSubmissions->concat($quizAttempts)
            ->sortByDesc('timestamp')
            ->values()
            ->all();

        // 4. Calculate averages
        $avgAssignments = $assignmentSubmissions->whereNotNull('score')->avg('score') ?? 0;
        $avgQuizzes = $quizAttempts->whereNotNull('score')->avg('score') ?? 0;

        return [
            'histories' => $histories,
            'avg_assignments' => $assignmentSubmissions->isEmpty() ? 'N/A' : round($avgAssignments, 1),
            'avg_quizzes' => $quizAttempts->isEmpty() ? 'N/A' : round($avgQuizzes, 1),
        ];
    }

    /**
     * Get all courses for filter dropdown
     */
    public function getTeacherCourses()
    {
        return Course::where('teacher_id', Auth::id())->get();
    }
}
