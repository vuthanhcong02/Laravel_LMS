<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Teacher\TeacherReportService;
use App\Http\Requests\Teacher\ReportIndexRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TeacherReportController extends Controller
{
    protected $reportService;

    public function __construct(TeacherReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Display a listing of students managed by the teacher.
     */
    public function index(ReportIndexRequest $request)
    {
        // Must be a teacher to access this, already handled by middleware 'role:2'
        
        $validated = $request->validated();

        $search = $validated['search'] ?? null;
        $courseId = $validated['course_id'] ?? null;

        $students = $this->reportService->getStudents($search, $courseId);
        $courses = $this->reportService->getTeacherCourses();

        return view('portal.teacher.reports.index', compact('students', 'courses', 'search', 'courseId'));
    }

    /**
     * Display the specified student's detailed report.
     */
    public function show(User $report) // Route param is {report} implicitly mapped to user because of resource
    {
        // {report} is a user ID in the url, model binding binds to User.
        $student = $report;

        // Verify that the requested user is actually a student (Security: IDOR & Logic check)
        if ($student->role !== User::ROLE_STUDENT) {
            abort(404, __('Không tìm thấy học viên.'));
        }

        // Service handles authorization check (IDOR)
        $studentData = $this->reportService->getStudentReport($student);

        // Fetch related stats
        $evaluationData = $this->reportService->getStudentEvaluationHistory($student);

        $stats = [
            'total_courses' => $student->enrollments()->whereHas('course', function($q) {
                $q->where('teacher_id', auth()->id());
            })->count(),
            'avg_assignments' => $evaluationData['avg_assignments'], 
            'avg_quizzes' => $evaluationData['avg_quizzes'],
        ];

        $histories = $evaluationData['histories'];

        return view('portal.teacher.reports.show', compact('student', 'stats', 'histories'));
    }

    /**
     * Export student's detailed report as PDF.
     */
    public function exportPdf(User $report)
    {
        $student = $report;

        // Verify that the requested user is actually a student (Security: IDOR & Logic check)
        if ($student->role !== User::ROLE_STUDENT) {
            abort(404, __('Không tìm thấy học viên.'));
        }

        // Service handles authorization check (IDOR) - Ensures teacher can only export their own students
        $studentData = $this->reportService->getStudentReport($student);

        // Fetch related stats and real history
        $evaluationData = $this->reportService->getStudentEvaluationHistory($student);

        $stats = [
            'total_courses' => $student->enrollments()->whereHas('course', function($q) {
                $q->where('teacher_id', auth()->id());
            })->count(),
            'avg_assignments' => $evaluationData['avg_assignments'], 
            'avg_quizzes' => $evaluationData['avg_quizzes'],
        ];

        $histories = $evaluationData['histories'];

        if (empty($histories)) {
            return back()->with('error', __('Học sinh này chưa có lịch sử đánh giá nào để xuất báo cáo.'));
        }

        $pdf = Pdf::loadView('portal.teacher.reports.pdf', compact('student', 'stats', 'histories'));
        
        $pdf->setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false
        ]);

        return $pdf->download(__('Bao_Cao_Hoc_Tap_') . str_replace(' ', '_', $student->first_name . '_' . $student->last_name) . '.pdf');
    }
}
