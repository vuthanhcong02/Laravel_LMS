<?php

namespace App\Services\Teacher;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TeacherLessonService
{
    /**
     * Store a new lesson with attached resources (record URL, PDF, note file, text notes).
     */
    public function storeLesson(Course $course, array $data): Lesson
    {
        $maxOrder = $course->lessons()->max('order') ?? 0;
        $data['order'] = $maxOrder + 1;

        // Process PDF file upload
        if (isset($data['pdf_file']) && $data['pdf_file'] instanceof UploadedFile) {
            $data['pdf_path'] = $data['pdf_file']->store('lessons/pdfs', 'public');
            unset($data['pdf_file']);
        }

        // Process note/attachment file upload
        if (isset($data['note_file']) && $data['note_file'] instanceof UploadedFile) {
            $data['note_file_path'] = $data['note_file']->store('lessons/notes', 'public');
            unset($data['note_file']);
        }

        // Synchronize video_url and record_url if only one is provided
        if (empty($data['video_url']) && !empty($data['record_url'])) {
            $data['video_url'] = $data['record_url'];
        } elseif (empty($data['record_url']) && !empty($data['video_url'])) {
            $data['record_url'] = $data['video_url'];
        }

        return $course->lessons()->create($data);
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
            if ($lesson->pdf_path && Storage::disk('public')->exists($lesson->pdf_path)) {
                Storage::disk('public')->delete($lesson->pdf_path);
            }
            $data['pdf_path'] = null;
        }

        // Handle new PDF file upload
        if (isset($data['pdf_file']) && $data['pdf_file'] instanceof UploadedFile) {
            if ($lesson->pdf_path && Storage::disk('public')->exists($lesson->pdf_path)) {
                Storage::disk('public')->delete($lesson->pdf_path);
            }
            $data['pdf_path'] = $data['pdf_file']->store('lessons/pdfs', 'public');
            unset($data['pdf_file']);
        }

        // Handle note file removal request
        if (!empty($data['remove_note_file'])) {
            if ($lesson->note_file_path && Storage::disk('public')->exists($lesson->note_file_path)) {
                Storage::disk('public')->delete($lesson->note_file_path);
            }
            $data['note_file_path'] = null;
        }

        // Handle new note file upload
        if (isset($data['note_file']) && $data['note_file'] instanceof UploadedFile) {
            if ($lesson->note_file_path && Storage::disk('public')->exists($lesson->note_file_path)) {
                Storage::disk('public')->delete($lesson->note_file_path);
            }
            $data['note_file_path'] = $data['note_file']->store('lessons/notes', 'public');
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

        // Delete attachment files from disk
        if ($lesson->pdf_path && Storage::disk('public')->exists($lesson->pdf_path)) {
            Storage::disk('public')->delete($lesson->pdf_path);
        }

        if ($lesson->note_file_path && Storage::disk('public')->exists($lesson->note_file_path)) {
            Storage::disk('public')->delete($lesson->note_file_path);
        }

        return (bool) $lesson->delete();
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
