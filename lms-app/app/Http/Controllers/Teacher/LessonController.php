<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\LessonRequest;
use App\Models\Course;
use App\Models\Lesson;
use App\Services\Teacher\TeacherLessonService;
use Illuminate\Http\RedirectResponse;

class LessonController extends Controller
{
    protected TeacherLessonService $lessonService;

    public function __construct(TeacherLessonService $lessonService)
    {
        $this->lessonService = $lessonService;
    }

    /**
     * Store a new lesson in the class.
     */
    public function store(LessonRequest $request, Course $course): RedirectResponse
    {
        $this->authorize('view', $course);

        $this->lessonService->storeLesson($course, $request->validated());

        return redirect()
            ->route('teacher.classes.show', ['course' => $course->id, 'tab' => 'lessons'])
            ->with('success', __('Đã thêm bài học mới vào lớp thành công!'));
    }

    /**
     * Update an existing lesson.
     */
    public function update(LessonRequest $request, Course $course, Lesson $lesson): RedirectResponse
    {
        $this->authorize('view', $course);

        $this->lessonService->updateLesson($course, $lesson, $request->validated());

        return redirect()
            ->route('teacher.classes.show', ['course' => $course->id, 'tab' => 'lessons'])
            ->with('success', __('Đã cập nhật bài học thành công!'));
    }

    /**
     * Delete a lesson from the class.
     */
    public function destroy(Course $course, Lesson $lesson): RedirectResponse
    {
        $this->authorize('view', $course);

        $this->lessonService->deleteLesson($course, $lesson);

        return redirect()
            ->route('teacher.classes.show', ['course' => $course->id, 'tab' => 'lessons'])
            ->with('success', __('Đã xóa bài học thành công!'));
    }

    /**
     * Move lesson order up (decrease order number).
     */
    public function moveUp(Course $course, Lesson $lesson): RedirectResponse
    {
        $this->authorize('view', $course);

        $this->lessonService->moveUp($course, $lesson);

        return redirect()
            ->route('teacher.classes.show', ['course' => $course->id, 'tab' => 'lessons'])
            ->with('success', __('Đã thay đổi thứ tự bài học.'));
    }

    /**
     * Move lesson order down (increase order number).
     */
    public function moveDown(Course $course, Lesson $lesson): RedirectResponse
    {
        $this->authorize('view', $course);

        $this->lessonService->moveDown($course, $lesson);

        return redirect()
            ->route('teacher.classes.show', ['course' => $course->id, 'tab' => 'lessons'])
            ->with('success', __('Đã thay đổi thứ tự bài học.'));
    }
}
