<?php

namespace App\Services\Teacher;

use App\Models\Course;
use App\Models\Lesson;
use App\Services\Media\MediaStorageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

        // Process PDF file upload to B2
        if ($pdfFile instanceof UploadedFile) {
            $lesson->pdf_path = app(MediaStorageService::class)->uploadLessonFile($course->id, $lesson->id, $pdfFile, 'pdfs');
        }

        // Process note/attachment file upload to B2
        if ($noteFile instanceof UploadedFile) {
            $lesson->note_file_path = app(MediaStorageService::class)->uploadLessonFile($course->id, $lesson->id, $noteFile, 'notes');
        }

        if ($pdfFile instanceof UploadedFile || $noteFile instanceof UploadedFile) {
            $lesson->save();
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

        // Handle new PDF file upload
        if (isset($data['pdf_file']) && $data['pdf_file'] instanceof UploadedFile) {
            if ($lesson->pdf_path) {
                $this->deleteResourceFile($lesson->pdf_path);
            }
            $data['pdf_path'] = app(MediaStorageService::class)->uploadLessonFile($course->id, $lesson->id, $data['pdf_file'], 'pdfs');
            unset($data['pdf_file']);
        }

        // Handle note file removal request
        if (!empty($data['remove_note_file'])) {
            if ($lesson->note_file_path) {
                $this->deleteResourceFile($lesson->note_file_path);
            }
            $data['note_file_path'] = null;
        }

        // Handle new note file upload
        if (isset($data['note_file']) && $data['note_file'] instanceof UploadedFile) {
            if ($lesson->note_file_path) {
                $this->deleteResourceFile($lesson->note_file_path);
            }
            $data['note_file_path'] = app(MediaStorageService::class)->uploadLessonFile($course->id, $lesson->id, $data['note_file'], 'notes');
            unset($data['note_file']);
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
