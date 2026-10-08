<?php

namespace App\Services\Teacher;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class AssignmentService
{
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
        $validated['attachments'] = $this->storeFiles($uploadedFiles, 'assignments');
        $validated['teacher_id'] = $teacherId;

        return Assignment::create($validated);
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

        // Delete discarded attachment files from disk
        $currentPaths = collect($assignment->attachments ?? [])->pluck('path')->all();
        $toDelete = array_diff($currentPaths, $keepPaths);
        foreach ($toDelete as $path) {
            Storage::disk('local')->delete($path);
        }

        // Add newly uploaded files
        $newFiles = $this->storeFiles($uploadedFiles, 'assignments');

        $validated['attachments'] = array_values(array_merge($kept, $newFiles));
        $assignment->update($validated);

        return $assignment->fresh();
    }

    /**
     * Grade a student assignment submission.
     */
    public function grade(AssignmentSubmission $submission, float $score, ?string $feedback, ?UploadedFile $audioFeedback = null, bool $deleteAudio = false): AssignmentSubmission
    {
        $data = [
            'score'            => $score,
            'teacher_feedback' => $feedback,
            'status'           => AssignmentSubmission::STATUS_GRADED,
        ];

        if ($audioFeedback) {
            // Delete old audio file before storing new one
            if ($submission->teacher_audio_path) {
                Storage::disk('local')->delete($submission->teacher_audio_path);
            }
            $path = $audioFeedback->store('feedback_audio', 'local');
            $data['teacher_audio_path'] = $path;
        } elseif ($deleteAudio) {
            if ($submission->teacher_audio_path) {
                Storage::disk('local')->delete($submission->teacher_audio_path);
            }
            $data['teacher_audio_path'] = null;
        }

        $submission->update($data);

        return $submission->fresh();
    }

    /**
     * Store multiple files, returning metadata array {name, path}.
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
