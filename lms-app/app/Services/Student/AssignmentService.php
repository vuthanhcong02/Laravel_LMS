<?php

namespace App\Services\Student;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Services\GamificationService;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AssignmentService
{
    public function __construct(private GamificationService $gamificationService)
    {
    }

    /**
     * Get courses and assignments grouped by lesson for student.
     *
     * @param int $userId
     * @return Collection
     */
    public function getGroupedByLessonForStudent(int $userId): Collection
    {
        $enrolledCourseIds = Enrollment::where('user_id', $userId)->pluck('course_id');

        return Course::whereIn('id', $enrolledCourseIds)
            ->with([
                'lessons' => function ($query) use ($userId) {
                    $query->orderBy('order')
                        ->with(['assignments' => function ($q) use ($userId) {
                            $q->where('status', Assignment::STATUS_PUBLISHED)
                              ->with(['submissions' => fn($sub) => $sub->where('user_id', $userId)])
                              ->latest();
                        }]);
                },
                'assignments' => function ($query) use ($userId) {
                    $query->where('status', Assignment::STATUS_PUBLISHED)
                          ->whereNull('lesson_id')
                          ->with(['submissions' => fn($sub) => $sub->where('user_id', $userId)])
                          ->latest();
                }
            ])
            ->get()
            ->filter(function ($course) {
                $hasLessonAssignments = $course->lessons->contains(fn($l) => $l->assignments->isNotEmpty());
                $hasGeneralAssignments = $course->assignments->isNotEmpty();
                return $hasLessonAssignments || $hasGeneralAssignments;
            })
            ->values();
    }

    /**
     * List published assignments for student with submission status.
     */
    public function listForStudent(int $userId): LengthAwarePaginator
    {
        $enrolledCourseIds = Enrollment::where('user_id', $userId)->pluck('course_id');

        return Assignment::whereIn('course_id', $enrolledCourseIds)
            ->where('status', Assignment::STATUS_PUBLISHED)
            ->with([
                'course',
                'lesson',
                'submissions' => fn($q) => $q->where('user_id', $userId),
            ])
            ->latest()
            ->paginate(15);
    }

    /**
     * Check if student is enrolled in the course of this assignment.
     */
    public function isEnrolled(int $userId, Assignment $assignment): bool
    {
        return Enrollment::where('user_id', $userId)
            ->where('course_id', $assignment->course_id)
            ->exists();
    }

    /**
     * Submit assignment (create or update if not yet graded) and award EXP to student.
     *
     * @param  UploadedFile[]  $files
     * @return array{submission: AssignmentSubmission, exp_result: array|null}
     */
    public function submit(int $userId, Assignment $assignment, array $files): array
    {
        $newAttachments = $this->storeFiles($files, 'submissions');

        // Wrap DB writes in a transaction to ensure atomicity
        $submission = DB::transaction(function () use ($userId, $assignment, $newAttachments) {
            $existing = AssignmentSubmission::where('assignment_id', $assignment->id)
                ->where('user_id', $userId)
                ->first();

            if ($existing) {
                // Delete old files before storing new attachments
                foreach ($existing->attachments ?? [] as $att) {
                    Storage::disk('local')->delete($att['path']);
                }
                $existing->update([
                    'attachments'      => $newAttachments,
                    'status'           => AssignmentSubmission::STATUS_SUBMITTED,
                    'score'            => null,
                    'teacher_feedback' => null,
                ]);
                return $existing->fresh();
            }

            return AssignmentSubmission::create([
                'assignment_id' => $assignment->id,
                'user_id'       => $userId,
                'status'        => AssignmentSubmission::STATUS_SUBMITTED,
                'attachments'   => $newAttachments,
            ]);
        });

        // Award EXP outside transaction — GamificationService handles its own idempotency
        $expResult = null;
        $user = User::find($userId);
        if ($user) {
            $expResult = $this->gamificationService->awardExp($user, 'course_assignment', $assignment->id);
        }

        return [
            'submission' => $submission,
            'exp_result' => $expResult,
        ];
    }

    /**
     * Store uploaded files and return metadata array {name, path}.
     *
     * @param  UploadedFile[]  $files
     */
    private function storeFiles(array $files, string $folder): array
    {
        $result = [];
        foreach ($files as $file) {
            $path = $file->store($folder, 'local');
            $result[] = [
                'name' => $file->getClientOriginalName(),
                'path' => $path,
            ];
        }
        return $result;
    }
}
