<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentAssignmentAndQuizShareLinkTest extends TestCase
{
    use RefreshDatabase;

    private User $enrolledStudent;
    private User $unenrolledStudent;
    private Course $course;
    private Lesson $lesson;
    private Assignment $publishedAssignment;
    private Assignment $draftAssignment;
    private Quiz $quiz;

    protected function setUp(): void
    {
        parent::setUp();

        $teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $this->course = Course::factory()->create(['teacher_id' => $teacher->id]);
        $this->lesson = Lesson::factory()->create(['course_id' => $this->course->id]);

        $this->enrolledStudent = User::factory()->create(['role' => User::ROLE_STUDENT]);
        Enrollment::create([
            'user_id'   => $this->enrolledStudent->id,
            'course_id' => $this->course->id,
        ]);

        $this->unenrolledStudent = User::factory()->create(['role' => User::ROLE_STUDENT]);

        $this->publishedAssignment = Assignment::create([
            'course_id'   => $this->course->id,
            'teacher_id'  => $teacher->id,
            'lesson_id'   => $this->lesson->id,
            'title'       => 'Published Assignment',
            'description' => 'Assignment description',
            'status'      => Assignment::STATUS_PUBLISHED,
        ]);

        $this->draftAssignment = Assignment::create([
            'course_id'   => $this->course->id,
            'teacher_id'  => $teacher->id,
            'lesson_id'   => $this->lesson->id,
            'title'       => 'Draft Assignment',
            'description' => 'Draft assignment description',
            'status'      => Assignment::STATUS_DRAFT,
        ]);

        $this->quiz = Quiz::create([
            'course_id'  => $this->course->id,
            'title'      => 'Quiz 1',
            'time_limit' => 30,
        ]);
    }

    /**
     * Unauthenticated guest should be redirected to login/home
     */
    public function test_guest_is_redirected_to_login_when_accessing_assignment_link(): void
    {
        $response = $this->get(route('student.assignments.show', $this->publishedAssignment->id));

        $response->assertRedirect(route('home'));
    }

    /**
     * Unauthenticated guest should be redirected to login/home
     */
    public function test_guest_is_redirected_to_login_when_accessing_quiz_link(): void
    {
        $response = $this->get(route('student.quizzes.show', $this->quiz->id));

        $response->assertRedirect(route('home'));
    }

    /**
     * Enrolled student should access published assignment
     */
    public function test_enrolled_student_can_access_published_assignment(): void
    {
        $response = $this->actingAs($this->enrolledStudent)
            ->get(route('student.assignments.show', $this->publishedAssignment->id));

        $response->assertRedirect(route('student.assignments.index', [
            'open'      => $this->publishedAssignment->id,
            'lesson_id' => $this->publishedAssignment->lesson_id,
        ]));
    }

    /**
     * Unenrolled student should receive 404 when accessing assignment
     */
    public function test_unenrolled_student_gets_404_when_accessing_assignment(): void
    {
        $response = $this->actingAs($this->unenrolledStudent)
            ->get(route('student.assignments.show', $this->publishedAssignment->id));

        $response->assertStatus(404);
    }

    /**
     * Draft assignment should return 404 even for enrolled students
     */
    public function test_draft_assignment_returns_404_even_for_enrolled_student(): void
    {
        $response = $this->actingAs($this->enrolledStudent)
            ->get(route('student.assignments.show', $this->draftAssignment->id));

        $response->assertStatus(404);
    }

    /**
     * Enrolled student should access quiz detail
     */
    public function test_enrolled_student_can_access_quiz(): void
    {
        $response = $this->actingAs($this->enrolledStudent)
            ->get(route('student.quizzes.show', $this->quiz->id));

        $response->assertStatus(200);
        $response->assertViewIs('portal.student.quizzes.show');
    }

    /**
     * Unenrolled student should receive 404 when accessing quiz
     */
    public function test_unenrolled_student_gets_404_when_accessing_quiz(): void
    {
        $response = $this->actingAs($this->unenrolledStudent)
            ->get(route('student.quizzes.show', $this->quiz->id));

        $response->assertStatus(404);
    }
}
