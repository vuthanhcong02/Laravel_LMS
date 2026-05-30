<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizQuestionsRequest;
use App\Models\Quiz;
use App\Services\Teacher\QuizService;
use Illuminate\Http\Request;

/**
 * Controller to manage Quizzes for Teachers
 */
class QuizController extends Controller
{
    /**
     * @param QuizService $quizService
     */
    public function __construct(private QuizService $quizService) {}

    /**
     * Display a listing of the quizzes.
     */
    public function index()
    {
        $this->authorize('viewAny', Quiz::class);
        $quizzes = $this->quizService->listForTeacher(auth()->id());

        return view('portal.teacher.quizzes.index', compact('quizzes'));
    }

    /**
     * Show the form for creating a new quiz.
     */
    public function create()
    {
        $this->authorize('create', Quiz::class);
        $courses = $this->quizService->teacherCourses(auth()->id());

        return view('portal.teacher.quizzes.create', compact('courses'));
    }

    /**
     * Store a newly created quiz in storage.
     */
    public function store(StoreQuizRequest $request)
    {
        $this->authorize('create', Quiz::class);
        $this->quizService->createQuiz($request->validated());

        return redirect()
            ->route('teacher.quizzes.index')
            ->with('success', __('Tạo bài thi thành công.'));
    }

    /**
     * Show the form for editing the specified quiz metadata.
     */
    public function edit(Quiz $quiz)
    {
        $this->authorize('update', $quiz);
        $courses = $this->quizService->teacherCourses(auth()->id());

        return view('portal.teacher.quizzes.edit', compact('quiz', 'courses'));
    }

    /**
     * Update the specified quiz metadata in storage.
     */
    public function update(StoreQuizRequest $request, Quiz $quiz)
    {
        $this->authorize('update', $quiz);
        $this->quizService->updateQuiz($quiz, $request->validated());

        return redirect()
            ->route('teacher.quizzes.index')
            ->with('success', __('Cập nhật bài thi thành công.'));
    }

    /**
     * Remove the specified quiz from storage.
     */
    public function destroy(Quiz $quiz)
    {
        $this->authorize('delete', $quiz);
        $this->quizService->deleteQuiz($quiz);

        return redirect()
            ->route('teacher.quizzes.index')
            ->with('success', __('Đã xóa bài thi.'));
    }

    /**
     * Show the form for managing questions of a quiz.
     */
    public function questions(Quiz $quiz)
    {
        $this->authorize('update', $quiz);
        $quiz->load('questions.options');

        return view('portal.teacher.quizzes.questions', compact('quiz'));
    }

    /**
     * Update/Save questions and options for a quiz.
     */
    public function updateQuestions(UpdateQuizQuestionsRequest $request, Quiz $quiz)
    {
        $this->authorize('update', $quiz);
        $this->quizService->saveQuestions($quiz, $request->validated()['questions'] ?? []);

        return back()->with('success', __('Cập nhật danh sách câu hỏi thành công.'));
    }

    /**
     * Import questions from CSV.
     */
    public function import(Request $request, Quiz $quiz)
    {
        $this->authorize('update', $quiz);
        
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $result = $this->quizService->importFromCsv($quiz, $request->file('csv_file'));

        if (!empty($result['errors'])) {
            return back()->with('error', implode('<br>', $result['errors']));
        }

        return back()->with('success', __('Đã nhập thành công :count câu hỏi.', ['count' => $result['count']]));
    }

    /**
     * Export CSV template for importing questions.
     */
    public function exportTemplate()
    {
        $csvContent = $this->quizService->generateCsvTemplate();
        $fileName = 'quiz_questions_template.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Show method placeholder to avoid undefined method errors.
     */
    public function show(Quiz $quiz)
    {
        return redirect()->route('teacher.quizzes.edit', $quiz);
    }
}
