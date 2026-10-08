<?php

namespace Tests\Feature;

use App\Enums\QuestionType;
use App\Enums\QuizType;
use App\Models\Category;
use App\Models\Course;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherQuizManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $teacher;
    protected User $otherTeacher;
    protected User $student;
    protected Course $course;

    protected function setUp(): void
    {
        parent::setUp();

        $this->teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $this->otherTeacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $this->student = User::factory()->create(['role' => User::ROLE_STUDENT]);

        $category = Category::create([
            'name' => 'HSK Category',
            'slug' => 'hsk-category',
            'type' => Category::TYPE_COURSE,
        ]);

        $this->course = Course::create([
            'teacher_id' => $this->teacher->id,
            'category_id' => $category->id,
            'title' => 'HSK 4 Class',
            'slug' => 'hsk-4-class',
        ]);
    }

    /**
     * Test that teacher can create a new quiz successfully.
     */
    public function test_teacher_can_create_quiz(): void
    {
        $response = $this->actingAs($this->teacher)->post(route('teacher.quizzes.store'), [
            'course_id' => $this->course->id,
            'title' => 'Midterm Exam HSK 4',
            'type' => QuizType::MIXED->value,
            'time_limit' => 45,
        ]);

        $response->assertRedirect(route('teacher.quizzes.index'));
        $this->assertDatabaseHas('quizzes', [
            'course_id' => $this->course->id,
            'title' => 'Midterm Exam HSK 4',
        ]);
    }

    /**
     * Test that teacher can update quiz questions including multiple choice and essay types.
     */
    public function test_teacher_can_update_quiz_questions(): void
    {
        $quiz = Quiz::create([
            'course_id' => $this->course->id,
            'title' => 'Practice Quiz',
            'type' => QuizType::MIXED,
            'time_limit' => 30,
        ]);

        $payload = [
            'questions' => [
                // 1. Multiple choice question
                [
                    'type' => QuestionType::MULTIPLE_CHOICE->value,
                    'question_text' => 'What is the capital of China?',
                    'marks' => 2,
                    'options' => [
                        ['option_text' => 'Beijing', 'is_correct' => 1],
                        ['option_text' => 'Shanghai', 'is_correct' => 0],
                    ],
                ],
                // 2. Essay question with auto-grading
                [
                    'type' => QuestionType::ESSAY->value,
                    'question_text' => 'Translate: I am a student',
                    'marks' => 3,
                    'essay_grading_type' => 'auto',
                    'correct_answer_text' => '我是学生;我是大学生',
                    'case_sensitive' => 0,
                ],
                // 3. Essay question with manual grading
                [
                    'type' => QuestionType::ESSAY->value,
                    'question_text' => 'Write a short paragraph about your hobbies',
                    'marks' => 5,
                    'essay_grading_type' => 'manual',
                    'correct_answer_text' => 'Rubric: correct grammar and 50+ characters',
                ],
            ],
        ];

        $response = $this->actingAs($this->teacher)->put(route('teacher.quizzes.questions.update', $quiz), $payload);

        $response->assertRedirect();
        $this->assertEquals(3, $quiz->questions()->count());
        $this->assertDatabaseHas('questions', [
            'quiz_id' => $quiz->id,
            'type' => QuestionType::ESSAY->value,
            'essay_grading_type' => 'manual',
        ]);
    }

    /**
     * Test that teacher can manually grade an essay attempt.
     */
    public function test_teacher_can_grade_essay_attempt(): void
    {
        $quiz = Quiz::create([
            'course_id' => $this->course->id,
            'title' => 'Writing Quiz',
            'type' => QuizType::ESSAY,
            'time_limit' => 30,
        ]);

        $question = Question::create([
            'quiz_id' => $quiz->id,
            'type' => QuestionType::ESSAY->value,
            'question_text' => 'Write a short paragraph',
            'marks' => 10,
            'essay_grading_type' => 'manual',
        ]);

        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => $this->student->id,
            'score' => 0,
            'started_at' => Carbon::now()->subMinutes(20),
            'completed_at' => Carbon::now(),
            'grading_status' => 'needs_grading',
        ]);

        $attemptAnswer = QuizAttemptAnswer::create([
            'attempt_id' => $attempt->id,
            'question_id' => $question->id,
            'text_answer' => 'My essay about autumn season...',
        ]);

        $response = $this->actingAs($this->teacher)->postJson(route('teacher.quizzes.grade-attempt', $attempt), [
            'grades' => [
                [
                    'question_id' => $question->id,
                    'marks_obtained' => 8.5,
                    'feedback' => 'Very expressive writing with rich vocabulary!',
                ],
            ],
        ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);

        $attempt->refresh();
        $attemptAnswer->refresh();

        $this->assertEquals('graded', $attempt->grading_status);
        $this->assertEquals(8.5, $attempt->score);
        $this->assertEquals(8.5, $attemptAnswer->marks_obtained);
        $this->assertEquals('Very expressive writing with rich vocabulary!', $attemptAnswer->teacher_feedback);
    }
}
