<?php

namespace App\Services\Teacher;

use App\Jobs\PurgeCloudMediaDirectoryJob;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Services\Media\MediaStorageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class AssignmentService
{
    /**
     * Inject MediaStorageService qua constructor thay vì dùng app() Service Locator.
     */
    public function __construct(private MediaStorageService $mediaService) {}

    /**
     * Get paginated assignments list for a teacher.
     */
    public function listForTeacher(int $teacherId): LengthAwarePaginator
    {
        return Assignment::where('teacher_id', $teacherId)
            ->with([
                'course' => function ($query) {
                    $query->withCount('enrollments');
                },
                'lesson',
                'submissions'
            ])
            ->latest()
            ->paginate(15);
    }

    /**
     * Get courses assigned to teacher.
     */
    public function teacherCourses(int $teacherId): Collection
    {
        return Course::where('teacher_id', $teacherId)
            ->with('lessons')
            ->get();
    }

    /**
     * Create a new assignment.
     */
    public function create(array $validated, array $uploadedFiles, int $teacherId): Assignment
    {
        $validated['attachments'] = [];
        $validated['teacher_id'] = $teacherId;
        $validated['status'] = $validated['status'] ?? Assignment::STATUS_PUBLISHED;

        $assignment = Assignment::create($validated);

        if (!empty($uploadedFiles)) {
            $assignment->update([
                'attachments' => $this->storeFiles($uploadedFiles, $assignment->id),
            ]);
        }

        return $assignment->fresh();
    }

    /**
     * Update an assignment, merging kept old files + newly uploaded files.
     */
    public function update(Assignment $assignment, array $validated, array $keepPaths, array $uploadedFiles): Assignment
    {
        // Filter kept old attachments
        $kept = array_filter(
            $assignment->attachments ?? [],
            fn($item) => in_array($item['path'], $keepPaths)
        );

        // Delete discarded attachment files from disk / B2
        $currentPaths = collect($assignment->attachments ?? [])->pluck('path')->all();
        $toDelete = array_diff($currentPaths, $keepPaths);
        foreach ($toDelete as $path) {
            $this->deleteFile($path);
        }

        // Add newly uploaded files
        $newFiles = $this->storeFiles($uploadedFiles, $assignment->id);

        $validated['attachments'] = array_values(array_merge($kept, $newFiles));
        $assignment->update($validated);

        return $assignment->fresh();
    }

    /**
     * Grade a student assignment submission.
     *
     * @param  UploadedFile[]  $newAttachments
     * @param  string[]  $keepExistingAttachmentPaths
     */
    /**
     * Grade a student assignment submission.
     *
     * @param  UploadedFile[]  $newAttachments
     * @param  string[]  $keepExistingAttachmentPaths
     */
    public function grade(
        AssignmentSubmission $submission,
        float $score,
        ?string $feedback,
        ?UploadedFile $audioFeedback = null,
        bool $deleteAudio = false,
        array $newAttachments = [],
        array $keepExistingAttachmentPaths = []
    ): AssignmentSubmission {
        $data = [
            'score'            => $score,
            'teacher_feedback' => $feedback,
            'status'           => AssignmentSubmission::STATUS_GRADED,
        ];

        // 1. Audio feedback
        if ($audioFeedback) {
            // Xóa file audio cũ trước khi lưu mới
            if ($submission->teacher_audio_path) {
                $this->deleteFile($submission->teacher_audio_path);
            }
            $path = $this->mediaService->uploadAssignmentFeedback(
                $submission->assignment_id,
                $submission->id,
                $audioFeedback
            );
            $data['teacher_audio_path'] = $path;
        } elseif ($deleteAudio) {
            if ($submission->teacher_audio_path) {
                $this->deleteFile($submission->teacher_audio_path);
            }
            $data['teacher_audio_path'] = null;
        }

        // 2. Feedback attachments (files/images)
        $retained = [];
        foreach ($submission->teacher_attachments ?? [] as $att) {
            $path = $att['path'] ?? null;
            if ($path && in_array($path, $keepExistingAttachmentPaths, true)) {
                $retained[] = $att;
            } else {
                $this->deleteFile($path);
            }
        }

        $uploaded = [];
        foreach ($newAttachments as $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $path = $this->mediaService->uploadAssignmentFeedbackAttachment(
                    $submission->assignment_id,
                    $submission->id,
                    $file
                );
                $uploaded[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                ];
            }
        }

        $data['teacher_attachments'] = array_values(array_merge($retained, $uploaded));

        $submission->update($data);

        return $submission->fresh();
    }

    /**
     * Store multiple files to B2, returning metadata array {name, path}.
     *
     * @param  UploadedFile[]  $files
     * @param  int  $assignmentId
     * @return array
     */
    /**
     * Store multiple files to B2, returning metadata array {name, path}.
     *
     * @param  UploadedFile[]  $files
     * @param  int  $assignmentId
     * @return array
     */
    private function storeFiles(array $files, int $assignmentId): array
    {
        $result = [];
        foreach ($files as $file) {
            $path = $this->mediaService->uploadAssignmentMaterial($assignmentId, $file);
            $result[] = [
                'name' => $file->getClientOriginalName(),
                'path' => $path,
            ];
        }
        return $result;
    }

    /**
     * Delete assignment and all its attached materials & student submission files.
     */
    public function delete(Assignment $assignment): void
    {
        $assignmentDir = "assignments/assignment_{$assignment->id}";

        // 1. Delete local directory if exists
        Storage::disk('local')->deleteDirectory($assignmentDir);

        // 2. Dispatch background job to purge all cloud files (materials, submissions, feedback)
        PurgeCloudMediaDirectoryJob::dispatch($assignmentDir);

        // 3. Delete database record (MySQL foreign key cascades submissions)
        $assignment->delete();
    }

    /**
     * Xóa file khỏi local storage hoặc B2.
     * Dùng delete trực tiếp thay vì gọi exists() trước để tiết kiệm network round-trip.
     *
     * @param string|null $path
     * @return void
     */
    private function deleteFile(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        // Xóa trên local disk (silent, không cần check exists)
        Storage::disk('local')->delete($path);

        // Xóa trên B2 nếu được cấu hình
        if (config('filesystems.disks.b2.key')) {
            try {
                Storage::disk('b2')->delete($path);
            } catch (Throwable $e) {
                Log::warning("Failed to delete assignment file from B2: {$path} — " . $e->getMessage());
            }
        }
    }
}
