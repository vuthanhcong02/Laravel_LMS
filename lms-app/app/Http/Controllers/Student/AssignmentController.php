<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitAssignmentRequest;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Services\Student\AssignmentService;

class AssignmentController extends Controller
{
    public function __construct(private AssignmentService $service) {}

    public function index()
    {
        $assignments = $this->service->listForStudent(auth()->id());

        return view('portal.student.assignments.index', compact('assignments'));
    }

    public function submit(SubmitAssignmentRequest $request, Assignment $assignment)
    {
        // Chỉ cho nộp bài khi assignment đã được publish
        abort_if($assignment->status !== Assignment::STATUS_PUBLISHED, 403, 'Bài tập chưa được mở.');

        // Kiểm tra hạn nộp bài
        if ($assignment->due_date && now()->gt($assignment->due_date)) {
            return back()->with('error', 'Đã hết hạn nộp bài.');
        }

        // Phải đăng ký khoá học
        abort_unless($this->service->isEnrolled(auth()->id(), $assignment), 403, 'Bạn chưa đăng ký khoá học này.');

        // Không cho nộp lại nếu đã được chấm điểm
        $existing = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing && $existing->status === AssignmentSubmission::STATUS_GRADED) {
            return back()->with('error', 'Bài đã được chấm điểm, không thể nộp lại.');
        }

        $this->service->submit(
            auth()->id(),
            $assignment,
            $request->file('attachments', [])
        );

        return back()->with('success', 'Nộp bài thành công!');
    }
}
