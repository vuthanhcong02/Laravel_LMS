<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitAssignmentRequest;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Services\Student\AssignmentService;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function __construct(private AssignmentService $service) {}

    public function index(Request $request)
    {
        $courses = $this->service->getGroupedByLessonForStudent(auth()->id());
        $openAssignmentId = $request->query('open') ? (int) $request->query('open') : null;
        $openLessonId = $request->query('lesson_id') ? (int) $request->query('lesson_id') : null;

        return view('portal.student.assignments.index', compact('courses', 'openAssignmentId', 'openLessonId'));
    }

    public function show(Assignment $assignment)
    {
        abort_unless($this->service->isEnrolled(auth()->id(), $assignment), 403, __('Bạn chưa đăng ký khoá học này.'));
        return redirect()->route('student.assignments.index', [
            'open'      => $assignment->id,
            'lesson_id' => $assignment->lesson_id,
        ]);
    }

    public function submit(SubmitAssignmentRequest $request, Assignment $assignment)
    {
        // Only allow submission when assignment is published
        abort_if($assignment->status !== Assignment::STATUS_PUBLISHED, 403, 'Bài tập chưa được mở.');

        // Check due date
        if ($assignment->due_date && now()->gt($assignment->due_date)) {
            return back()->with('error', 'Đã hết hạn nộp bài.');
        }

        // Must be enrolled in the course
        abort_unless($this->service->isEnrolled(auth()->id(), $assignment), 403, 'Bạn chưa đăng ký khoá học này.');

        // Do not allow resubmission if already graded
        $existing = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing && $existing->status === AssignmentSubmission::STATUS_GRADED) {
            return back()->with('error', 'Bài đã được chấm điểm, không thể nộp lại.');
        }

        $files = $request->getValidFiles();

        $result = $this->service->submit(
            auth()->id(),
            $assignment,
            $files
        );

        $msg = __('Nộp bài thành công!');

        return back()->with('success', $msg);
    }
}
