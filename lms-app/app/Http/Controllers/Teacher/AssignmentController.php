<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\GradeSubmissionRequest;
use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Services\Teacher\AssignmentService;

class AssignmentController extends Controller
{
    public function __construct(private AssignmentService $assignmentService) {}

    public function index()
    {
        $assignments = $this->assignmentService->listForTeacher(auth()->id());

        return view('portal.teacher.assignments.index', compact('assignments'));
    }

    public function create()
    {
        $courses = $this->assignmentService->teacherCourses(auth()->id());

        return view('portal.teacher.assignments.create', compact('courses'));
    }

    public function store(StoreAssignmentRequest $request)
    {
        $this->assignmentService->create(
            $request->safe()->except('attachments'),
            $request->file('attachments', []),
            auth()->id()
        );

        return redirect()
            ->route('teacher.assignments.index')
            ->with('success', 'Tạo bài tập thành công.');
    }

    public function edit(Assignment $assignment)
    {
        $this->authorize('update', $assignment);

        $courses = $this->assignmentService->teacherCourses(auth()->id());

        return view('portal.teacher.assignments.edit', compact('assignment', 'courses'));
    }

    public function update(UpdateAssignmentRequest $request, Assignment $assignment)
    {
        $this->authorize('update', $assignment);

        $this->assignmentService->update(
            $assignment,
            $request->safe()->except(['attachments', 'keep_attachments']),
            $request->input('keep_attachments', []),
            $request->file('attachments', [])
        );

        return redirect()
            ->route('teacher.assignments.index')
            ->with('success', 'Cập nhật bài tập thành công.');
    }

    public function show(Assignment $assignment)
    {
        $this->authorize('view', $assignment);

        $submissions = $assignment->submissions()
            ->with('user')
            ->latest()
            ->paginate(15);

        return view('portal.teacher.assignments.show', compact('assignment', 'submissions'));
    }

    public function destroy(Assignment $assignment)
    {
        $this->authorize('delete', $assignment);

        $assignment->delete();

        return redirect()
            ->route('teacher.assignments.index')
            ->with('success', 'Đã xoá bài tập.');
    }

    public function grade(GradeSubmissionRequest $request, Assignment $assignment, AssignmentSubmission $submission)
    {
        $this->authorize('grade', $assignment);

        abort_if(!$this->authorize('grade', $assignment), 403);

        abort_if($submission->assignment_id !== $assignment->id, 404);

        $this->assignmentService->grade(
            $submission,
            $request->score,
            $request->teacher_feedback,
            $request->file('audio_feedback'),
            $request->boolean('delete_audio')
        );

        return back()->with('success', 'Chấm điểm thành công.');
    }
}
