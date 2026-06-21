<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\Student\StudentQuizService;
use Illuminate\Http\Request;

class StudentQuizController extends Controller
{
    /**
     * @param StudentQuizService $studentQuizService
     */
    public function __construct(private StudentQuizService $studentQuizService) {}

    /**
     * Display student's quizzes list
     */
    public function index()
    {
        $quizzes = $this->studentQuizService->listForStudent(auth()->id());

        return view('portal.student.quizzes.index', compact('quizzes'));
    }

    /**
     * Display details of a specific quiz before taking it
     */
    public function show($quizId)
    {
        $quiz = $this->studentQuizService->getQuizDetails($quizId, auth()->id());

        return view('portal.student.quizzes.show', compact('quiz'));
    }

    /**
     * Create a new attempt and redirect to the test page
     */
    public function attempt($quizId)
    {
        $attempt = $this->studentQuizService->startAttempt($quizId, auth()->id());

        return redirect()->route('student.quizzes.take', $attempt->id);
    }

    /**
     * Show the test screen (taking the test)
     */
    public function take($attemptId)
    {
        try {
            $attempt = $this->studentQuizService->getAttemptForTaking($attemptId, auth()->id());
            return view('portal.student.quizzes.take', compact('attempt'));
        } catch (\Exception $e) {
            return redirect()
                ->route('student.quizzes.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Submit the completed test
     */
    public function submit(Request $request, $attemptId)
    {
        $answers = $request->input('answers', []);
        
        try {
            $attempt = $this->studentQuizService->submitAttempt($attemptId, auth()->id(), $answers);
            return redirect()
                ->route('student.quizzes.result', $attempt->id)
                ->with('success', __('Nộp bài thi thành công!'));
        } catch (\Exception $e) {
            return redirect()
                ->route('student.quizzes.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Show test results after submission
     */
    public function result($attemptId)
    {
        try {
            $attempt = $this->studentQuizService->getAttemptResult($attemptId, auth()->id());
            return view('portal.student.quizzes.result', compact('attempt'));
        } catch (\Exception $e) {
            return redirect()
                ->route('student.quizzes.index')
                ->with('error', $e->getMessage());
        }
    }
}
