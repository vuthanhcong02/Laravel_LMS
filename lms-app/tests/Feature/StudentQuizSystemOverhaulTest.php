<?php

namespace Tests\Feature;

use App\Enums\QuestionType;
use App\Enums\QuizType;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentQuizSystemOverhaulTest extends TestCase
{
    use RefreshDatabase;

    private User $student;
    private User $teacher;
    private Course $course;

    protected function setUp(): void
    {
        parent::setUp();

        $this->teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $this->course = Course::factory()->create(['teacher_id' => $this->teacher->id]);

        $this->student = User::factory()->create(['role' => User::ROLE_STUDENT]);
        Enrollment::create([
            'user_id'   => $this->student->id,
            'course_id' => $this->course->id,
        ]);
    }

    public function test_teacher_can_create_quiz_with_due_date(): void
    {
        $dueDate = Carbon::now()->addDays(5)->format('Y-m-d H:i:s');

        $response = $this->actingAs($this->teacher)
            ->post(route('teacher.quizzes.store'), [
                'course_id'   => $this->course->id,
                'title'       => 'Kiểm tra 15 phút có deadline',
                'description' => 'Mô tả bài kiểm tra',
                'type'        => QuizType::MULTIPLE_CHOICE->value,
                'time_limit'  => 15,
                'pass_score'  => 5.0,
                'due_date'    => $dueDate,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('quizzes', [
            'title'      => 'Kiểm tra 15 phút có deadline',
            'time_limit' => 15,
            'due_date'   => $dueDate,
        ]);
    }

    public function test_student_cannot_attempt_expired_quiz(): void
    {
        $pastDueDate = Carbon::now()->subDay();

        $quiz = Quiz::create([
            'course_id'   => $this->course->id,
            'teacher_id'  => $this->teacher->id,
            'title'       => 'Bài thi hết hạn',
            'type'        => QuizType::MULTIPLE_CHOICE->value,
            'time_limit'  => 10,
            'pass_score'  => 5.0,
            'due_date'    => $pastDueDate,
        ]);

        $response = $this->actingAs($this->student)
            ->post(route('student.quizzes.attempt', $quiz->id));

        $response->assertRedirect(route('student.quizzes.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('quiz_attempts', [
            'quiz_id' => $quiz->id,
            'user_id' => $this->student->id,
        ]);
    }

    public function test_student_enforces_single_attempt_rule(): void
    {
        $quiz = Quiz::create([
            'course_id'   => $this->course->id,
            'teacher_id'  => $this->teacher->id,
            'title'       => 'Bài thi 1 lần duy nhất',
            'type'        => QuizType::MULTIPLE_CHOICE->value,
            'time_limit'  => 10,
            'pass_score'  => 5.0,
        ]);

        // First attempt: should succeed and create an attempt
        $res1 = $this->actingAs($this->student)
            ->post(route('student.quizzes.attempt', $quiz->id));

        $attempt = QuizAttempt::where('quiz_id', $quiz->id)->where('user_id', $this->student->id)->first();
        $this->assertNotNull($attempt);
        $res1->assertRedirect(route('student.quizzes.take', $attempt->id));

        // Mark attempt as completed
        $attempt->update(['completed_at' => Carbon::now(), 'score' => 8.5]);

        // Second attempt attempt: must redirect to result instead of creating new attempt
        $res2 = $this->actingAs($this->student)
            ->post(route('student.quizzes.attempt', $quiz->id));

        $res2->assertRedirect(route('student.quizzes.result', $attempt->id));
        $this->assertEquals(1, QuizAttempt::where('quiz_id', $quiz->id)->where('user_id', $this->student->id)->count());
    }

    public function test_score_is_normalized_to_scale_of_ten_from_custom_marks(): void
    {
        $quiz = Quiz::create([
            'course_id'   => $this->course->id,
            'teacher_id'  => $this->teacher->id,
            'title'       => 'Bài thi kiểm tra thang điểm 10',
            'type'        => QuizType::MULTIPLE_CHOICE->value,
            'time_limit'  => 20,
            'pass_score'  => 5.0,
        ]);

        // Question 1: 5 marks
        $q1 = Question::create([
            'quiz_id'       => $quiz->id,
            'question_text' => 'Câu hỏi 1 (5 điểm)',
            'type'          => QuestionType::MULTIPLE_CHOICE->value,
            'marks'         => 5,
        ]);
        $opt1A = Option::create([
            'question_id' => $q1->id,
            'option_text' => 'Đáp án đúng 1',
            'is_correct'  => true,
        ]);
        $opt1B = Option::create([
            'question_id' => $q1->id,
            'option_text' => 'Đáp án sai 1',
            'is_correct'  => false,
        ]);

        // Question 2: 15 marks (Total quiz marks = 20)
        $q2 = Question::create([
            'quiz_id'       => $quiz->id,
            'question_text' => 'Câu hỏi 2 (15 điểm)',
            'type'          => QuestionType::MULTIPLE_CHOICE->value,
            'marks'         => 15,
        ]);
        $opt2A = Option::create([
            'question_id' => $q2->id,
            'option_text' => 'Đáp án đúng 2',
            'is_correct'  => true,
        ]);
        $opt2B = Option::create([
            'question_id' => $q2->id,
            'option_text' => 'Đáp án sai 2',
            'is_correct'  => false,
        ]);

        $attempt = QuizAttempt::create([
            'quiz_id'    => $quiz->id,
            'user_id'    => $this->student->id,
            'started_at' => Carbon::now(),
        ]);

        // Student answers Q1 correctly (5 marks), Q2 incorrectly (0 marks)
        // Raw score: 5 / 20. Normalized score: (5 / 20) * 10 = 2.5 / 10.
        $submitData = [
            'answers' => [
                $q1->id => $opt1A->id,
                $q2->id => $opt2B->id,
            ],
        ];

        $response = $this->actingAs($this->student)
            ->post(route('student.quizzes.submit', $attempt->id), $submitData);

        $response->assertRedirect(route('student.quizzes.result', $attempt->id));

        $attempt->refresh();
        $this->assertEquals(5.0, (float) $attempt->raw_score);
        $this->assertEquals(20.0, (float) $attempt->total_marks);
        $this->assertEquals(2.5, (float) $attempt->score);
    }

    public function test_timeout_auto_submits_and_redirects_when_student_has_answers(): void
    {
        $quiz = Quiz::create([
            'course_id'   => $this->course->id,
            'teacher_id'  => $this->teacher->id,
            'title'       => 'Bài thi 10 phút bị quá giờ',
            'type'        => QuizType::MULTIPLE_CHOICE->value,
            'time_limit'  => 10,
            'pass_score'  => 5.0,
        ]);

        $q = Question::create([
            'quiz_id'       => $quiz->id,
            'question_text' => 'Câu hỏi mẫu',
            'type'          => QuestionType::MULTIPLE_CHOICE->value,
            'marks'         => 10,
        ]);
        $opt = Option::create([
            'question_id' => $q->id,
            'option_text' => 'Đáp án đúng',
            'is_correct'  => true,
        ]);

        // Student started 15 minutes ago
        $attempt = QuizAttempt::create([
            'quiz_id'    => $quiz->id,
            'user_id'    => $this->student->id,
            'started_at' => Carbon::now()->subMinutes(15),
        ]);

        // Student already answered
        \App\Models\QuizAttemptAnswer::create([
            'attempt_id'     => $attempt->id,
            'question_id'    => $q->id,
            'option_id'      => $opt->id,
            'marks_obtained' => 10.0,
            'is_correct'     => true,
        ]);

        // Student accesses take() URL
        $response = $this->actingAs($this->student)
            ->get(route('student.quizzes.take', $attempt->id));

        // Must redirect to result instead of throwing 400 Bad Request
        $response->assertRedirect(route('student.quizzes.result', $attempt->id));
        $response->assertSessionHas('info');

        $attempt->refresh();
        $this->assertNotNull($attempt->completed_at);
        $this->assertEquals(10.0, (float) $attempt->score);
    }

    public function test_empty_attempt_is_reset_when_due_date_is_still_open(): void
    {
        $quiz = Quiz::create([
            'course_id'   => $this->course->id,
            'teacher_id'  => $this->teacher->id,
            'title'       => 'Bài thi còn hạn due_date',
            'type'        => QuizType::MULTIPLE_CHOICE->value,
            'time_limit'  => 10,
            'pass_score'  => 5.0,
            'due_date'    => Carbon::now()->addDay(),
        ]);

        // Student started 15 minutes ago but submitted 0 answers
        $attempt = QuizAttempt::create([
            'quiz_id'    => $quiz->id,
            'user_id'    => $this->student->id,
            'started_at' => Carbon::now()->subMinutes(15),
        ]);

        // Student accesses take() URL -> should reset and redirect to briefing page
        $response = $this->actingAs($this->student)
            ->get(route('student.quizzes.take', $attempt->id));

        $response->assertRedirect(route('student.quizzes.show', $quiz->id));

        // The stale empty attempt must be deleted
        $this->assertDatabaseMissing('quiz_attempts', [
            'id' => $attempt->id,
        ]);

        // Student can start afresh
        $startRes = $this->actingAs($this->student)
            ->post(route('student.quizzes.attempt', $quiz->id));

        $newAttempt = QuizAttempt::where('quiz_id', $quiz->id)->where('user_id', $this->student->id)->first();
        $this->assertNotNull($newAttempt);
        $startRes->assertRedirect(route('student.quizzes.take', $newAttempt->id));
    }
}
