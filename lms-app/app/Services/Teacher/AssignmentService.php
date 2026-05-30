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
     * Lấy danh sách bài tập của giáo viên (paginated).
     */
    public function listForTeacher(int $teacherId): LengthAwarePaginator
    {
        return Assignment::where('teacher_id', $teacherId)
            ->with(['course', 'lesson', 'submissions'])
            ->latest()
            ->paginate(15);
    }

    /**
     * Lấy danh sách khoá học mà giáo viên phụ trách.
     */
    public function teacherCourses(int $teacherId): Collection
    {
        return Course::where('teacher_id', $teacherId)
            ->with('lessons')
            ->get();
    }

    /**
     * Tạo bài tập mới.
     */
    public function create(array $validated, array $uploadedFiles, int $teacherId): Assignment
    {
        $validated['attachments'] = $this->storeFiles($uploadedFiles, 'assignments');
        $validated['teacher_id'] = $teacherId;

        return Assignment::create($validated);
    }

    /**
     * Cập nhật bài tập, hợp nhất file cũ giữ lại + file mới.
     */
    public function update(Assignment $assignment, array $validated, array $keepPaths, array $uploadedFiles): Assignment
    {
        // Lọc file cũ được giữ lại
        $kept = array_filter(
            $assignment->attachments ?? [],
            fn($item) => in_array($item['path'], $keepPaths)
        );

        // Xoá các file attachment không còn được giữ lại
        $currentPaths = collect($assignment->attachments ?? [])->pluck('path')->all();
        $toDelete = array_diff($currentPaths, $keepPaths);
        foreach ($toDelete as $path) {
            Storage::disk('local')->delete($path);
        }

        // Thêm file mới
        $newFiles = $this->storeFiles($uploadedFiles, 'assignments');

        $validated['attachments'] = array_values(array_merge($kept, $newFiles));
        $assignment->update($validated);

        return $assignment->fresh();
    }

    /**
     * Chấm điểm bài nộp.
     */
    public function grade(AssignmentSubmission $submission, float $score, ?string $feedback, ?UploadedFile $audioFeedback = null, bool $deleteAudio = false): AssignmentSubmission
    {
        $data = [
            'score'            => $score,
            'teacher_feedback' => $feedback,
            'status'           => AssignmentSubmission::STATUS_GRADED,
        ];

        if ($audioFeedback) {
            // Xoá file audio cũ trước khi lưu file mới
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
     * Upload nhiều files, trả về mảng meta {name, path}.
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
