<?php

namespace App\Services\Admin;

use App\Models\Course;
use App\Models\Lesson;

class LessonService
{
    /**
     * Append a new lesson to a course (auto-increments order).
     */
    public function store(Course $course, array $data): Lesson
    {
        $data['order'] = ($course->lessons()->max('order') ?? 0) + 1;

        return $course->lessons()->create($data);
    }

    /**
     * Update a lesson's details.
     */
    public function update(Lesson $lesson, array $data): Lesson
    {
        $lesson->update($data);

        return $lesson;
    }

    /**
     * Delete a lesson.
     */
    public function destroy(Lesson $lesson): void
    {
        $lesson->delete();
    }

    /**
     * Swap the order of a lesson with the one directly above it.
     */
    public function moveUp(Lesson $lesson): void
    {
        $prev = $lesson->course->lessons()
            ->where('order', '<', $lesson->order)
            ->orderByDesc('order')
            ->first();

        if ($prev) {
            [$lesson->order, $prev->order] = [$prev->order, $lesson->order];
            $lesson->save();
            $prev->save();
        }
    }

    /**
     * Swap the order of a lesson with the one directly below it.
     */
    public function moveDown(Lesson $lesson): void
    {
        $next = $lesson->course->lessons()
            ->where('order', '>', $lesson->order)
            ->orderBy('order')
            ->first();

        if ($next) {
            [$lesson->order, $next->order] = [$next->order, $lesson->order];
            $lesson->save();
            $next->save();
        }
    }
}
