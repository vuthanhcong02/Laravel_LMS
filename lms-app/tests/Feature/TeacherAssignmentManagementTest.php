<?php

namespace Tests\Feature;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherAssignmentManagementTest extends TestCase
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
        $this->student = User::factory()->create([
            'role' => User::ROLE_STUDENT,
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $category = Category::create([
            'name' => 'HSK Category',
            'slug' => 'hsk-category',
            'type' => Category::TYPE_COURSE,
        ]);

        $this->course = Course::create([
            'teacher_id' => $this->teacher->id,
            'category_id' => $category->id,
            'title' => 'HSK 3 Standard',
            'slug' => 'hsk-3-standard',
        ]);
    }

    /**
     * Test that teacher can create a new assignment successfully.
     */
    public function test_teacher_can_create_assignment(): void
    {
        $response = $this->actingAs($this->teacher)->post(route('teacher.assignments.store'), [
            'course_id' => $this->course->id,
            'title' => 'Translation Assignment Week 1',
            'description' => 'Translate passage into English',
            'status' => Assignment::STATUS_PUBLISHED,
            'due_date' => Carbon::now()->addDays(5)->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect(route('teacher.assignments.index'));
        $this->assertDatabaseHas('assignments', [
            'course_id' => $this->course->id,
            'teacher_id' => $this->teacher->id,
            'title' => 'Translation Assignment Week 1',
        ]);
    }

    /**
     * Test that teacher can update an existing assignment.
     */
    public function test_teacher_can_update_assignment(): void
    {
        $assignment = Assignment::create([
            'teacher_id' => $this->teacher->id,
            'course_id' => $this->course->id,
            'title' => 'Old Assignment Title',
            'due_date' => Carbon::now()->addDays(2),
            'status' => Assignment::STATUS_PUBLISHED,
        ]);

        $response = $this->actingAs($this->teacher)->put(route('teacher.assignments.update', $assignment), [
            'course_id' => $this->course->id,
            'title' => 'Updated Assignment Title',
            'description' => 'Updated description content',
            'status' => Assignment::STATUS_PUBLISHED,
            'due_date' => Carbon::now()->addDays(7)->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect(route('teacher.assignments.index'));
        $this->assertDatabaseHas('assignments', [
            'id' => $assignment->id,
            'title' => 'Updated Assignment Title',
        ]);
    }

    /**
     * Test that teacher can view submissions and grade a student's submission.
     */
    public function test_teacher_can_view_and_grade_student_submission(): void
    {
        $assignment = Assignment::create([
            'teacher_id' => $this->teacher->id,
            'course_id' => $this->course->id,
            'title' => 'Grammar Assignment',
            'due_date' => Carbon::now()->addDays(3),
            'status' => Assignment::STATUS_PUBLISHED,
        ]);

        $submission = AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'user_id' => $this->student->id,
            'status' => AssignmentSubmission::STATUS_SUBMITTED,
            'content' => 'Student response content...',
        ]);

        // 1. View submissions list
        $responseView = $this->actingAs($this->teacher)->get(route('teacher.assignments.show', $assignment));
        $responseView->assertOk();
        $responseView->assertSee($this->student->first_name);

        // 2. Grade submission with score and feedback
        $responseGrade = $this->actingAs($this->teacher)->post(route('teacher.assignments.grade', [$assignment, $submission]), [
            'score' => 9.5,
            'teacher_feedback' => 'Great work and accurate grammar!',
        ]);

        $responseGrade->assertSessionHasNoErrors();
        $submission->refresh();
        $this->assertEquals(9.5, $submission->score);
        $this->assertEquals(AssignmentSubmission::STATUS_GRADED, $submission->status);
        $this->assertEquals('Great work and accurate grammar!', $submission->teacher_feedback);
    }

    /**
     * Test that teacher can delete an assignment.
     */
    public function test_teacher_can_delete_assignment(): void
    {
        $assignment = Assignment::create([
            'teacher_id' => $this->teacher->id,
            'course_id' => $this->course->id,
            'title' => 'Assignment to delete',
            'due_date' => Carbon::now()->addDays(1),
            'status' => Assignment::STATUS_DRAFT,
        ]);

        $response = $this->actingAs($this->teacher)->delete(route('teacher.assignments.destroy', $assignment));

        $response->assertRedirect(route('teacher.assignments.index'));
        $this->assertDatabaseMissing('assignments', ['id' => $assignment->id]);
    }

    /**
     * Test that a teacher cannot view, edit, or delete another teacher's assignment (Authorization & IDOR).
     */
    public function test_other_teacher_cannot_manage_foreign_assignment(): void
    {
        $assignment = Assignment::create([
            'teacher_id' => $this->teacher->id,
            'course_id' => $this->course->id,
            'title' => 'Private Assignment',
            'due_date' => Carbon::now()->addDays(2),
            'status' => Assignment::STATUS_PUBLISHED,
        ]);

        $responseEdit = $this->actingAs($this->otherTeacher)->get(route('teacher.assignments.edit', $assignment));
        $responseEdit->assertForbidden();

        $responseDelete = $this->actingAs($this->otherTeacher)->delete(route('teacher.assignments.destroy', $assignment));
        $responseDelete->assertForbidden();
    }
}
