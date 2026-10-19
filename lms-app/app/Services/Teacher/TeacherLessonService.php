<?php

namespace App\Services\Teacher;

use App\Jobs\UploadLessonMaterialsJob;
use App\Models\Course;
use App\Models\Lesson;
use App\Services\Media\MediaStorageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class TeacherLessonService
{
    /**
     * Store a new lesson with attached resources (record URL, PDF, note file, text notes).
     */
    public function storeLesson(Course $course, array $data): Lesson
    {
        $maxOrder = $course->lessons()->max('order') ?? 0;
        $data['order'] = $maxOrder + 1;

        $pdfFile = $data['pdf_file'] ?? null;
        $noteFile = $data['note_file'] ?? null;
        unset($data['pdf_file'], $data['note_file']);

        // Synchronize video_url and record_url if only one is provided
        if (empty($data['video_url']) && !empty($data['record_url'])) {
            $data['video_url'] = $data['record_url'];
        } elseif (empty($data['record_url']) && !empty($data['video_url'])) {
            $data['record_url'] = $data['video_url'];
        }

        $lesson = $course->lessons()->create($data);

        // Dispatch PDF slide upload to background queue
        if ($pdfFile instanceof UploadedFile) {
            $tempDir = storage_path("app/temp_lessons/{$lesson->id}");
            File::makeDirectory($tempDir, 0775, true, true);
            $ext = $pdfFile->getClientOriginalExtension() ?: 'pdf';
            $tempFileName = "pdf_" . time() . "_" . Str::random(6) . ".{$ext}";
            $pdfFile->move($tempDir, $tempFileName);
            $tempFilePath = "{$tempDir}/{$tempFileName}";

            UploadLessonMaterialsJob::dispatch($course->id, $lesson->id, $tempFilePath, 'pdfs');
        }

        // Dispatch note attachment upload to background queue
        if ($noteFile instanceof UploadedFile) {
            $tempDir = storage_path("app/temp_lessons/{$lesson->id}");
            File::makeDirectory($tempDir, 0775, true, true);
            $ext = $noteFile->getClientOriginalExtension() ?: 'bin';
            $tempFileName = "note_" . time() . "_" . Str::random(6) . ".{$ext}";
            $noteFile->move($tempDir, $tempFileName);
            $tempFilePath = "{$tempDir}/{$tempFileName}";

            UploadLessonMaterialsJob::dispatch($course->id, $lesson->id, $tempFilePath, 'notes');
        }

        return $lesson;
    }

    /**
     * Update lesson info and attached resources.
     */
    public function updateLesson(Course $course, Lesson $lesson, array $data): Lesson
    {
        if ($lesson->course_id !== $course->id) {
            abort(403, __('Bài học không thuộc về lớp học này.'));
        }

        // Handle PDF file removal request
        if (!empty($data['remove_pdf'])) {
            if ($lesson->pdf_path) {
                $this->deleteResourceFile($lesson->pdf_path);
            }
            $data['pdf_path'] = null;
        }

        // Dispatch updated PDF slide upload to background queue
        if (isset($data['pdf_file']) && $data['pdf_file'] instanceof UploadedFile) {
            $pdfUpload = $data['pdf_file'];
            unset($data['pdf_file']);

            $oldPdfPath = $lesson->pdf_path;
            $tempDir = storage_path("app/temp_lessons/{$lesson->id}");
            File::makeDirectory($tempDir, 0775, true, true);
            $ext = $pdfUpload->getClientOriginalExtension() ?: 'pdf';
            $tempFileName = "pdf_" . time() . "_" . Str::random(6) . ".{$ext}";
            $pdfUpload->move($tempDir, $tempFileName);
            $tempFilePath = "{$tempDir}/{$tempFileName}";

            UploadLessonMaterialsJob::dispatch($course->id, $lesson->id, $tempFilePath, 'pdfs', $oldPdfPath);
        }

        // Handle note file removal request
        if (!empty($data['remove_note_file'])) {
            if ($lesson->note_file_path) {
                $this->deleteResourceFile($lesson->note_file_path);
            }
            $data['note_file_path'] = null;
        }

        // Dispatch updated note file upload to background queue
        if (isset($data['note_file']) && $data['note_file'] instanceof UploadedFile) {
            $noteUpload = $data['note_file'];
            unset($data['note_file']);

            $oldNotePath = $lesson->note_file_path;
            $tempDir = storage_path("app/temp_lessons/{$lesson->id}");
            File::makeDirectory($tempDir, 0775, true, true);
            $ext = $noteUpload->getClientOriginalExtension() ?: 'bin';
            $tempFileName = "note_" . time() . "_" . Str::random(6) . ".{$ext}";
            $noteUpload->move($tempDir, $tempFileName);
            $tempFilePath = "{$tempDir}/{$tempFileName}";

            UploadLessonMaterialsJob::dispatch($course->id, $lesson->id, $tempFilePath, 'notes', $oldNotePath);
        }

        // Synchronize video_url and record_url if only one is provided
        if (empty($data['video_url']) && !empty($data['record_url'])) {
            $data['video_url'] = $data['record_url'];
        } elseif (empty($data['record_url']) && !empty($data['video_url'])) {
            $data['record_url'] = $data['video_url'];
        }

        $lesson->update($data);

        return $lesson;
    }

    /**
     * Delete a lesson and cleanup storage files.
     */
    public function deleteLesson(Course $course, Lesson $lesson): bool
    {
        if ($lesson->course_id !== $course->id) {
            abort(403, __('Bài học không thuộc về lớp học này.'));
        }

        // Delete attachment files from both B2 and local storage
        if ($lesson->pdf_path) {
            $this->deleteResourceFile($lesson->pdf_path);
        }

        if ($lesson->note_file_path) {
            $this->deleteResourceFile($lesson->note_file_path);
        }

        // Cleanup lesson directory on B2
        try {
            app(MediaStorageService::class)->deleteDirectory("courses/course_{$course->id}/lessons/lesson_{$lesson->id}");
        } catch (Throwable $e) {
            // Ignore directory cleanup error
        }

        return (bool) $lesson->delete();
    }

    /**
     * Delete a single resource file from B2 and local disk.
     */
    protected function deleteResourceFile(string $path): void
    {
        try {
            Storage::disk('b2')->delete($path);
        } catch (Throwable $e) {
            // Ignore B2 delete error
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Move lesson order up.
     */
    public function moveUp(Course $course, Lesson $lesson): bool
    {
        if ($lesson->course_id !== $course->id) {
            return false;
        }

        $prev = $course->lessons()
            ->where('order', '<', $lesson->order)
            ->orderByDesc('order')
            ->first();

        if ($prev) {
            $tempOrder = $lesson->order;
            $lesson->update(['order' => $prev->order]);
            $prev->update(['order' => $tempOrder]);
            return true;
        }

        return false;
    }

    /**
     * Move lesson order down.
     */
    public function moveDown(Course $course, Lesson $lesson): bool
    {
        if ($lesson->course_id !== $course->id) {
            return false;
        }

        $next = $course->lessons()
            ->where('order', '>', $lesson->order)
            ->orderBy('order')
            ->first();

        if ($next) {
            $tempOrder = $lesson->order;
            $lesson->update(['order' => $next->order]);
            $next->update(['order' => $tempOrder]);
            return true;
        }

        return false;
    }
}
