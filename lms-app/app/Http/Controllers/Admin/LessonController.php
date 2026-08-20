<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Lesson\LessonRequest;
use App\Models\Course;
use App\Models\Lesson;
use App\Services\Admin\LessonService;
use Illuminate\Support\Facades\Log;

class LessonController extends Controller
{
    public function __construct(protected LessonService $service) {}

    public function store(LessonRequest $request, Course $course)
    {
        try {
            $this->service->store($course, $request->validated());

            return redirect()->route('admin.courses.edit', $course)
                ->with('success', 'Bài học đã được thêm.');
        } catch (\Exception $e) {
            Log::error('Error adding lesson: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi thêm bài học.');
        }
    }

    public function update(LessonRequest $request, Lesson $lesson)
    {
        try {
            $this->service->update($lesson, $request->validated());

            return redirect()->route('admin.courses.edit', $lesson->course_id)
                ->with('success', 'Bài học đã được cập nhật.');
        } catch (\Exception $e) {
            Log::error('Error updating lesson: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi cập nhật bài học.');
        }
    }

    public function destroy(Lesson $lesson)
    {
        try {
            $courseId = $lesson->course_id;
            $this->service->destroy($lesson);

            return redirect()->route('admin.courses.edit', $courseId)
                ->with('success', 'Bài học đã được xoá.');
        } catch (\Exception $e) {
            Log::error('Error deleting lesson: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xoá bài học.');
        }
    }

    public function moveUp(Lesson $lesson)
    {
        try {
            $this->service->moveUp($lesson);

            return redirect()->route('admin.courses.edit', $lesson->course_id)
                ->with('success', 'Đã di chuyển bài học lên.');
        } catch (\Exception $e) {
            Log::error('Error moving lesson up: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi di chuyển bài học lên.');
        }
    }

    public function moveDown(Lesson $lesson)
    {
        try {
            $this->service->moveDown($lesson);

            return redirect()->route('admin.courses.edit', $lesson->course_id)
                ->with('success', 'Đã di chuyển bài học xuống.');
        } catch (\Exception $e) {
            Log::error('Error moving lesson down: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi di chuyển bài học xuống.');
        }
    }
}
