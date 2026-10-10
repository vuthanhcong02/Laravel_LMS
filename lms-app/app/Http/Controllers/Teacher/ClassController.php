<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\EnrollStudentRequest;
use App\Http\Requests\Teacher\SearchStudentRequest;
use App\Models\Course;
use App\Models\Enrollment;
use App\Services\Teacher\TeacherClassService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassController extends Controller
{
    protected TeacherClassService $teacherClassService;

    public function __construct(TeacherClassService $teacherClassService)
    {
        $this->teacherClassService = $teacherClassService;
    }

    /**
     * Display a listing of courses managed by the teacher.
     */
    public function index(): View
    {
        $this->authorize('viewAny', Course::class);

        $classes = $this->teacherClassService->getTeacherClasses();

        return view('portal.teacher.classes.index', compact('classes'));
    }

    /**
     * Display the specified course details (Curriculum & Students list).
     */
    public function show(SearchStudentRequest $request, Course $course): View
    {
        $this->authorize('view', $course);

        $course->loadCount('enrollments');
        $course->load([
            'lessons' => fn($q) => $q->orderBy('order', 'asc'),
            'quizzes',
        ]);

        $search = $request->query('search');
        $enrollments = $this->teacherClassService->getPaginatedStudents($course, $search);
        $stats = $this->teacherClassService->getClassStats($course);

        return view('portal.teacher.classes.show', [
            'class'       => $course,
            'enrollments' => $enrollments,
            'search'      => $search,
            'stats'       => $stats,
        ]);
    }

    /**
     * Search available students who can be enrolled into the class.
     */
    public function availableStudents(Request $request, Course $course): JsonResponse
    {
        $this->authorize('view', $course);

        $search = $request->query('q');
        $students = $this->teacherClassService->getAvailableStudents($course, $search);

        return response()->json([
            'students' => $students->map(fn($u) => [
                'id'         => $u->id,
                'name'       => $u->full_name,
                'email'      => $u->email,
                'avatar_url' => $u->avatar_url,
                'level'      => $u->level_badge ?? 'Lv.1',
            ]),
        ]);
    }

    /**
     * Enroll one or multiple students into the class.
     */
    public function enroll(EnrollStudentRequest $request, Course $course): RedirectResponse
    {
        $this->authorize('view', $course);

        $userIds = $request->validated('user_ids');
        if (empty($userIds) && $request->filled('user_id')) {
            $userIds = [(int) $request->validated('user_id')];
        }

        $count = $this->teacherClassService->enrollStudents($course, (array) $userIds);

        $message = $count > 1 
            ? __('Đã thêm :count học viên vào lớp học thành công!', ['count' => $count])
            : __('Đã thêm học viên vào lớp học thành công!');

        $tab = $request->input('tab', 'students');

        return redirect()
            ->route('teacher.classes.show', ['course' => $course->id, 'tab' => $tab])
            ->with('success', $message);
    }

    /**
     * Unenroll/remove a student from the class.
     */
    public function unenroll(Request $request, Course $course, Enrollment $enrollment): RedirectResponse
    {
        $this->authorize('view', $course);

        if ($enrollment->course_id !== $course->id) {
            abort(403, __('Yêu cầu không hợp lệ.'));
        }

        $this->teacherClassService->removeStudent($course, $enrollment->id);

        $tab = $request->input('tab', 'students');

        return redirect()
            ->route('teacher.classes.show', ['course' => $course->id, 'tab' => $tab])
            ->with('success', __('Đã xóa học viên khỏi lớp học thành công!'));
    }
}
