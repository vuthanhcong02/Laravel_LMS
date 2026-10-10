<?php

namespace Tests\Feature;

use App\Enums\EnrollmentStatus;
use App\Enums\QuestionType;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use App\Services\Student\AssignmentService;
use App\Services\Student\StudentQuizService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudentSidebarBadgeTest extends TestCase
{
    use DatabaseTransactions;

    public function test_sidebar_displays_and_clears_assignment_and_quiz_badges()
    {
        Storage::fake('local');

        $user = User::factory()->create(['role' => User::ROLE_STUDENT]);
        $teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $course = Course::factory()->create(['teacher_id' => $teacher->id]);

        Enrollment::create([
            'user_id'   => $user->id,
            'course_id' => $course->id,
            'status'    => EnrollmentStatus::ACTIVE,
        ]);

        // Create 2 published assignments
        $assignment1 = Assignment::create([
            'course_id'    => $course->id,
            'teacher_id'   => $teacher->id,
            'title'        => 'Bài tập 1',
            'description'  => 'Mô tả bài tập 1',
            'status'       => Assignment::STATUS_PUBLISHED,
        ]);

        $assignment2 = Assignment::create([
            'course_id'    => $course->id,
            'teacher_id'   => $teacher->id,
            'title'        => 'Bài tập 2',
            'description'  => 'Mô tả bài tập 2',
            'status'       => Assignment::STATUS_PUBLISHED,
        ]);

        // Create 1 quiz
        $quiz = Quiz::create([
            'course_id'  => $course->id,
            'title'      => 'Bài kiểm tra 1',
            'time_limit' => 20,
        ]);
        $q = Question::create([
            'quiz_id'       => $quiz->id,
            'question_text' => 'Câu hỏi test?',
            'type'          => QuestionType::MULTIPLE_CHOICE,
            'marks'         => 10,
        ]);
        $opt = Option::create([
            'question_id' => $q->id,
            'option_text' => 'Đáp án',
            'is_correct'  => true,
        ]);

        // Authenticate and access student dashboard
        $response = $this->actingAs($user)->get(route('student.dashboard'));
        $response->assertStatus(200);

        // Verify view composer variables
        $response->assertViewHas('pendingAssignmentsCount', 2);
        $response->assertViewHas('pendingQuizzesCount', 1);

        // Student submits assignment 1
        $file = UploadedFile::fake()->create('bai1.pdf', 50);
        app(AssignmentService::class)->submit($user->id, $assignment1, [$file]);

        $responseAfter1 = $this->actingAs($user)->get(route('student.dashboard'));
        $responseAfter1->assertViewHas('pendingAssignmentsCount', 1);

        // Student submits assignment 2
        app(AssignmentService::class)->submit($user->id, $assignment2, [$file]);

        $responseAfter2 = $this->actingAs($user)->get(route('student.dashboard'));
        $responseAfter2->assertViewHas('pendingAssignmentsCount', 0);

        // Student completes and submits quiz
        $quizService = app(StudentQuizService::class);
        $attempt = $quizService->startAttempt($quiz->id, $user->id);
        $quizService->submitAttempt($attempt->id, $user->id, [
            $q->id => ['option_id' => $opt->id]
        ]);

        $responseAfterQuiz = $this->actingAs($user)->get(route('student.dashboard'));
        $responseAfterQuiz->assertViewHas('pendingQuizzesCount', 0);
    }
}
