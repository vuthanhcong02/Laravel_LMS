<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\Student\StudentQuizService;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        try {
            $attempt = $this->studentQuizService->startAttempt($quizId, auth()->id());

            if ($attempt->completed_at) {
                return redirect()->route('student.quizzes.result', $attempt->id);
            }

            return redirect()->route('student.quizzes.take', $attempt->id);
        } catch (HttpException $e) {
            if ($e->getStatusCode() === 403) {
                return redirect()->route('student.quizzes.index')->with('error', $e->getMessage() ?: __('Bài kiểm tra này đã quá hạn nộp.'));
            }

            throw $e;
        }
    }

    /**
     * Show the test screen (taking the test)
     */
    public function take($attemptId)
    {
        try {
            $attempt = $this->studentQuizService->getAttemptForTaking($attemptId, auth()->id());

            // If attempt is completed or auto-submitted upon expiration, redirect cleanly to result view
            if ($attempt->completed_at) {
                return redirect()
                    ->route('student.quizzes.result', $attempt->id)
                    ->with('info', __('Thời gian làm bài đã kết thúc. Hệ thống đã tự động ghi nhận kết quả bài thi của bạn.'));
            }

            return view('portal.student.quizzes.take', compact('attempt'));
        } catch (HttpException $e) {
            if ($e->getStatusCode() === 303) {
                return redirect($e->getMessage())
                    ->with('info', __('Lần làm bài trước đó đã hết thời gian nhưng bạn chưa trả lời câu nào. Bạn có thể bắt đầu lại bài thi.'));
            }

            return redirect()
                ->route('student.quizzes.index')
                ->with('error', $e->getMessage());
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
            $result = $this->studentQuizService->submitAttempt($attemptId, auth()->id(), $answers);
            $attempt = $result['attempt'];
            $expGained = $result['exp_result']['exp_gained'] ?? null;

            $message = __('Nộp bài thi thành công!');
            if ($expGained) {
                $message .= ' ' . __('(+ :exp EXP)', ['exp' => $expGained]);
            }

            return redirect()
                ->route('student.quizzes.result', $attempt->id)
                ->with('success', $message)
                ->with('exp_gained', $expGained);
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
