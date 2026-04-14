<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Http\Requests\Teacher\SearchStudentRequest;
use App\Services\Teacher\TeacherClassService;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    protected $teacherClassService;

    public function __construct(TeacherClassService $teacherClassService)
    {
        $this->teacherClassService = $teacherClassService;
    }

    /**
     * Display a listing of the teacher's classes (courses).
     */
    public function index()
    {
        $this->authorize('viewAny', Course::class);

        $classes = $this->teacherClassService->getTeacherClasses();

        return view('portal.teacher.classes.index', compact('classes'));
    }

    /**
     * Display the specified class (course) details.
     */
    public function show(SearchStudentRequest $request, Course $course)
    {
        $this->authorize('view', $course);

        $course->loadCount('enrollments');
        $course->load(['lessons', 'quizzes']);

        $search = $request->query('search');
        $enrollments = $this->teacherClassService->getPaginatedStudents($course, $search);

        return view('portal.teacher.classes.show', [
            'class' => $course,
            'enrollments' => $enrollments,
            'search' => $search
        ]);
    }
}
