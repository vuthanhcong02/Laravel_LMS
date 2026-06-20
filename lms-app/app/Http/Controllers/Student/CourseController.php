<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the student's enrolled courses.
     */
    public function index()
    {
        $enrollments = Enrollment::with(['course.category', 'course.teacher'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(9);

        // Optional: Pre-calculate progress if not stored in DB
        // Assuming $enrollment->course->lessons_count etc. will be loaded in the view

        return view('portal.student.courses.index', compact('enrollments'));
    }

    /**
     * Display the specified enrolled course details.
     */
    public function show(Course $course)
    {
        // Security check: Make sure student is enrolled in this course
        $enrollment = Enrollment::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->firstOrFail();

        // Load necessary relationships
        $course->load([
            'teacher',
            'category',
            'lessons',
            'quizzes',
            'assignments' => function($q) {
                $q->where('status', \App\Models\Assignment::STATUS_PUBLISHED)
                  ->with(['submissions' => function($sq) {
                      $sq->where('user_id', auth()->id());
                  }]);
            }
        ]);

        return view('portal.student.courses.show', compact('course', 'enrollment'));
    }

    /**
     * Demo UI for learning mode (video player).
     */
    public function learn(Course $course, $lessonId = null)
    {
        // Security check
        $enrollment = Enrollment::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->firstOrFail();

        $course->load(['lessons' => function($q) {
            $q->orderBy('order');
        }]);

        // Mock a current lesson for demo
        $currentLesson = $lessonId ? $course->lessons->where('id', $lessonId)->first() : $course->lessons->first();

        return view('portal.student.courses.learn', compact('course', 'currentLesson'));
    }
}
