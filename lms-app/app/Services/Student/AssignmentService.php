<?php

namespace App\Services\Student;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Enrollment;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class AssignmentService
{
    /**
     * Lấy danh sách bài tập published của học sinh (kèm submission riêng).
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
     * Kiểm tra học sinh có đăng ký khoá học của bài tập này không.
     */
    public function isEnrolled(int $userId, Assignment $assignment): bool
    {
        return Enrollment::where('user_id', $userId)
            ->where('course_id', $assignment->course_id)
            ->exists();
    }

    /**
     * Nộp bài (tạo mới hoặc cập nhật nếu chưa chấm).
     *
     * @param  UploadedFile[]  $files
     */
    public function submit(int $userId, Assignment $assignment, array $files): AssignmentSubmission
    {
        $existing = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('user_id', $userId)
            ->first();

        $newAttachments = $this->storeFiles($files, 'submissions');

        if ($existing) {
            // Xoá file cũ trước khi lưu file mới
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
