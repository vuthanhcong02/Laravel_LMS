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

class StudentAssignmentAndQuizExpTest extends TestCase
{
    use DatabaseTransactions;

    public function test_student_earns_exp_when_submitting_assignment()
    {
        Storage::fake('local');

        $user = User::factory()->create(['exp_total' => 0]);
        $teacher = User::factory()->create();
        $course = Course::factory()->create(['teacher_id' => $teacher->id]);

        Enrollment::create([
            'user_id'   => $user->id,
            'course_id' => $course->id,
            'status'    => EnrollmentStatus::ACTIVE,
        ]);

        $assignment = Assignment::create([
            'course_id'    => $course->id,
            'teacher_id'   => $teacher->id,
            'title'        => 'Bài tập HSK 1',
            'description'  => 'Làm bài tập ngữ pháp',
            'status'       => Assignment::STATUS_PUBLISHED,
        ]);

        $file = UploadedFile::fake()->create('submission.pdf', 100);

        $service = app(AssignmentService::class);
        $result = $service->submit($user->id, $assignment, [$file]);

        $this->assertNotNull($result['exp_result']);
        $this->assertEquals(25, $result['exp_result']['exp_gained']);

        $user->refresh();
        $this->assertEquals(25, $user->exp_total);

        // Resubmitting assignment does not grant extra EXP because one_time = true
        $secondResult = $service->submit($user->id, $assignment, [$file]);
        $this->assertNull($secondResult['exp_result']);
        $user->refresh();
        $this->assertEquals(25, $user->exp_total);
    }

    public function test_student_earns_exp_when_submitting_quiz_with_passing_score()
    {
        $user = User::factory()->create(['exp_total' => 0]);
        $teacher = User::factory()->create();
        $course = Course::factory()->create(['teacher_id' => $teacher->id]);

        Enrollment::create([
            'user_id'   => $user->id,
            'course_id' => $course->id,
            'status'    => EnrollmentStatus::ACTIVE,
        ]);

        $quiz = Quiz::create([
            'course_id'   => $course->id,
            'title'       => 'Bài kiểm tra số 1',
            'time_limit'  => 30,
        ]);

        $q1 = Question::create([
            'quiz_id'       => $quiz->id,
            'question_text' => '你好 nghĩa là gì?',
            'type'          => QuestionType::MULTIPLE_CHOICE,
            'marks'         => 10,
        ]);

        $optCorrect = Option::create([
            'question_id' => $q1->id,
            'option_text' => 'Xin chào',
            'is_correct'  => true,
        ]);

        $optWrong = Option::create([
            'question_id' => $q1->id,
            'option_text' => 'Tạm biệt',
            'is_correct'  => false,
        ]);

        $service = app(StudentQuizService::class);
        $attempt = $service->startAttempt($quiz->id, $user->id);

        $submitResult = $service->submitAttempt($attempt->id, $user->id, [
            $q1->id => ['option_id' => $optCorrect->id]
        ]);

        $this->assertNotNull($submitResult['exp_result']);
        $this->assertEquals(30, $submitResult['exp_result']['exp_gained']);

        $user->refresh();
        $this->assertEquals(30, $user->exp_total);
    }
}
