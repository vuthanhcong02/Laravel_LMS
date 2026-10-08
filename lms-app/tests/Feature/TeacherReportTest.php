<?php

namespace Tests\Feature;

use App\Enums\EnrollmentStatus;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherReportTest extends TestCase
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
            'last_name' => 'Student',
        ]);

        $category = Category::create([
            'name' => 'Report Category',
            'slug' => 'report-category',
            'type' => Category::TYPE_COURSE,
        ]);

        $this->course = Course::create([
            'teacher_id' => $this->teacher->id,
            'category_id' => $category->id,
            'title' => 'Chinese HSK 2 Course',
            'slug' => 'chinese-hsk-2-course',
        ]);

        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->course->id,
            'status' => EnrollmentStatus::ACTIVE,
        ]);
    }

    /**
     * Test that teacher can view reports index and filter by course.
     */
    public function test_teacher_can_view_reports_index_and_filter_by_course(): void
    {
        // 1. View all students in reports index
        $response = $this->actingAs($this->teacher)->get(route('teacher.reports.index'));
        $response->assertOk();
        $response->assertViewHas('students');

        // 2. Filter students by specific course
        $responseFilter = $this->actingAs($this->teacher)->get(route('teacher.reports.index', [
            'course_id' => $this->course->id,
        ]));
        $responseFilter->assertOk();
    }

    /**
     * Test that teacher can view detailed student performance report.
     */
    public function test_teacher_can_view_student_detailed_report(): void
    {
        $assignment = Assignment::create([
            'teacher_id' => $this->teacher->id,
            'course_id' => $this->course->id,
            'title' => 'Assignment 1',
            'max_score' => 10,
        ]);

        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'user_id' => $this->student->id,
            'status' => AssignmentSubmission::STATUS_GRADED,
            'score' => 9,
        ]);

        $response = $this->actingAs($this->teacher)->get(route('teacher.reports.show', $this->student));

        $response->assertOk();
        $response->assertViewIs('portal.teacher.reports.show');
        $response->assertViewHasAll(['student', 'stats', 'histories']);
    }

    /**
     * Test that a teacher cannot view reports of students from other teachers' courses (IDOR prevention).
     */
    public function test_other_teacher_cannot_view_foreign_student_report(): void
    {
        $response = $this->actingAs($this->otherTeacher)->get(route('teacher.reports.show', $this->student));

        // Must be forbidden or not found since student is not enrolled in this teacher's courses
        $this->assertTrue(in_array($response->status(), [403, 404]));
    }
}
