<?php

namespace App\Services\Teacher;

use App\Enums\EnrollmentStatus;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\HskMockExamResult;
use App\Models\Quiz;
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

    /**
     * Lấy dữ liệu bài tập, bài kiểm tra và kết quả thi HSK của học sinh trong lớp.
     *
     * @param Course $course
     * @return array{
     *     assignments: \Illuminate\Database\Eloquent\Collection,
     *     quizzes: \Illuminate\Database\Eloquent\Collection,
     *     hsk_results: \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection,
     *     summary: array<string, mixed>
     * }
     */
    public function getClassAssessmentsData(Course $course): array
    {
        // 1. Danh sách học sinh đã ghi danh vào lớp
        $enrolledStudents = $course->enrollments()
            ->with('user')
            ->get()
            ->map(fn($enrollment) => $enrollment->user)
            ->filter()
            ->values();

        $studentUserIds = $enrolledStudents->pluck('id')->toArray();

        // 2. Danh sách bài tập của lớp kèm số lượt nộp và danh sách học viên
        $assignments = $course->assignments()
            ->withCount('submissions')
            ->with([
                'lesson',
                'submissions' => function ($q) {
                    $q->with('user');
                }
            ])
            ->latest('created_at')
            ->get()
            ->map(function ($assignment) use ($enrolledStudents) {
                $submissionsByUserId = $assignment->submissions->keyBy('user_id');
                $assignment->students_submissions_list = $enrolledStudents->map(function ($student) use ($submissionsByUserId) {
                    $submission = $submissionsByUserId->get($student->id);
                    $hasSubmitted = $submission !== null;
                    return [
                        'id'            => $student->id,
                        'name'          => $student->full_name,
                        'email'         => $student->email,
                        'avatar_url'    => $student->avatar_url,
                        'first_char'    => mb_substr($student->first_name, 0, 1),
                        'has_submitted' => $hasSubmitted,
                        'score'         => $submission?->score,
                        'status'        => $submission?->status,
                        'is_graded'     => $submission?->status === AssignmentSubmission::STATUS_GRADED,
                        'is_pending'    => $submission?->status === AssignmentSubmission::STATUS_SUBMITTED,
                        'submitted_at'  => $submission?->created_at ? $submission->created_at->format('d/m/Y H:i') : null,
                    ];
                })->values();

                return $assignment;
            });

        // 3. Danh sách bài kiểm tra của lớp kèm lượt làm bài và danh sách học viên
        $quizzes = $course->quizzes()
            ->withCount(['attempts', 'questions'])
            ->with([
                'attempts' => function ($q) {
                    $q->with('user');
                }
            ])
            ->latest('created_at')
            ->get()
            ->map(function ($quiz) use ($enrolledStudents) {
                $attemptsByUserId = $quiz->attempts->keyBy('user_id');
                $quiz->students_attempts_list = $enrolledStudents->map(function ($student) use ($attemptsByUserId) {
                    $attempt = $attemptsByUserId->get($student->id);
                    return [
                        'id'            => $student->id,
                        'name'          => $student->full_name,
                        'email'         => $student->email,
                        'avatar_url'    => $student->avatar_url,
                        'first_char'    => mb_substr($student->first_name, 0, 1),
                        'has_attempted' => $attempt !== null,
                        'score'         => $attempt?->score,
                        'completed_at'  => $attempt?->completed_at ? $attempt->completed_at->format('d/m/Y H:i') : null,
                    ];
                })->values();

                return $quiz;
            });

        // 4. Kết quả thi thử HSK của các học sinh thuộc lớp này
        $hskResults = !empty($studentUserIds)
            ? HskMockExamResult::whereIn('user_id', $studentUserIds)
                ->with(['user', 'mockExam.hskLevel'])
                ->where('status', 'completed')
                ->latest('completed_at')
                ->get()
            : collect();

        // 5. Thống kê tổng quan
        $totalAssignments = $assignments->count();
        $totalQuizzes = $quizzes->count();

        // Đếm bài tập nộp đang chờ chấm điểm
        $pendingSubmissionsCount = 0;
        foreach ($assignments as $assignment) {
            $pendingSubmissionsCount += $assignment->submissions
                ->where('status', AssignmentSubmission::STATUS_SUBMITTED)
                ->count();
        }

        // Tính điểm trung bình Quiz của lớp
        $allQuizScores = [];
        foreach ($quizzes as $quiz) {
            foreach ($quiz->attempts as $attempt) {
                if ($attempt->score !== null && $attempt->status === 'completed') {
                    $allQuizScores[] = $attempt->score;
                }
            }
        }
        $avgQuizScore = count($allQuizScores) > 0 ? round(array_sum($allQuizScores) / count($allQuizScores), 1) : null;

        return [
            'assignments' => $assignments,
            'quizzes'     => $quizzes,
            'hsk_results' => $hskResults,
            'summary'     => [
                'total_assignments'         => $totalAssignments,
                'total_quizzes'             => $totalQuizzes,
                'pending_submissions_count' => $pendingSubmissionsCount,
                'avg_quiz_score'            => $avgQuizScore,
                'total_hsk_attempts'        => $hskResults->count(),
            ],
        ];
    }
}

