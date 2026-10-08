<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Question;
use App\Models\Option;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use App\Models\User;
use App\Enums\EnrollmentStatus;
use App\Enums\QuestionType;
use App\Enums\QuizType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherQuizResultsTest extends TestCase
{
    use RefreshDatabase;

    protected User $teacher;
    protected User $otherTeacher;
    protected User $student1;
    protected User $student2;
    protected Course $course;
    protected Quiz $quiz;

    protected function setUp(): void
    {
        parent::setUp();

        $this->teacher = User::factory()->create([
            'role' => User::ROLE_TEACHER,
        ]);

        $this->otherTeacher = User::factory()->create([
            'role' => User::ROLE_TEACHER,
        ]);

        $this->student1 = User::factory()->create([
            'role' => User::ROLE_STUDENT,
        ]);

        $this->student2 = User::factory()->create([
            'role' => User::ROLE_STUDENT,
        ]);

        $this->course = Course::factory()->create([
            'teacher_id' => $this->teacher->id,
        ]);

        // Enroll students
        Enrollment::create([
            'user_id' => $this->student1->id,
            'course_id' => $this->course->id,
            'status' => EnrollmentStatus::ACTIVE,
        ]);

        Enrollment::create([
            'user_id' => $this->student2->id,
            'course_id' => $this->course->id,
            'status' => EnrollmentStatus::ACTIVE,
        ]);

        // Create Quiz
        $this->quiz = Quiz::create([
            'course_id' => $this->course->id,
            'title' => 'Bài kiểm tra HSK 1 - Giữa kỳ',
            'type' => QuizType::MULTIPLE_CHOICE,
            'time_limit' => 30,
        ]);

        // Create 2 Questions
        $q1 = Question::create([
            'quiz_id' => $this->quiz->id,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'question_text' => 'Câu 1: Thủ đô của Việt Nam là gì?',
            'marks' => 5,
        ]);
        Option::create(['question_id' => $q1->id, 'option_text' => 'Hà Nội', 'is_correct' => true]);
        Option::create(['question_id' => $q1->id, 'option_text' => 'TP.HCM', 'is_correct' => false]);

        $q2 = Question::create([
            'quiz_id' => $this->quiz->id,
            'type' => QuestionType::MULTIPLE_CHOICE,
            'question_text' => 'Câu 2: Bạn khỏe không dịch sang tiếng Trung là?',
            'marks' => 5,
        ]);
        Option::create(['question_id' => $q2->id, 'option_text' => '你好吗', 'is_correct' => true]);
        Option::create(['question_id' => $q2->id, 'option_text' => '谢谢', 'is_correct' => false]);
    }

    /**
     * Test teacher can view quiz results and stats page.
     */
    public function test_teacher_can_view_quiz_results_page(): void
    {
        // Student 1 submitted quiz attempt with 10 points
        $attempt = QuizAttempt::create([
            'quiz_id' => $this->quiz->id,
            'user_id' => $this->student1->id,
            'score' => 10,
            'started_at' => now()->subMinutes(15),
            'completed_at' => now(),
        ]);

        $response = $this->actingAs($this->teacher)
            ->get(route('teacher.quizzes.results', $this->quiz->id));

        $response->assertOk();
        $response->assertViewIs('portal.teacher.quizzes.results');
        $response->assertSee('Bài kiểm tra HSK 1 - Giữa kỳ');
        $response->assertSee($this->student1->full_name);
        $response->assertSee($this->student2->full_name);
    }

    /**
     * Test other teacher cannot view results of foreign quiz.
     */
    public function test_other_teacher_cannot_view_foreign_quiz_results(): void
    {
        $response = $this->actingAs($this->otherTeacher)
            ->get(route('teacher.quizzes.results', $this->quiz->id));

        $response->assertForbidden();
    }

    /**
     * Test teacher can view JSON detail of a student's attempt.
     */
    public function test_teacher_can_view_attempt_detail_json(): void
    {
        $attempt = QuizAttempt::create([
            'quiz_id' => $this->quiz->id,
            'user_id' => $this->student1->id,
            'score' => 10,
            'started_at' => now()->subMinutes(15),
            'completed_at' => now(),
        ]);

        $response = $this->actingAs($this->teacher)
            ->json('GET', route('teacher.quizzes.attempt-detail', $attempt->id));

        $response->assertOk();
        $response->assertJsonStructure([
            'attempt',
            'student',
            'quiz',
            'total_marks',
            'score',
            'percentage',
            'questions' => [
                '*' => [
                    'id',
                    'question_text',
                    'marks',
                    'options'
                ]
            ]
        ]);
    }
}
