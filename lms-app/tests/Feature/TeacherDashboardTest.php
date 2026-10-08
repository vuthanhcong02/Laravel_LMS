<?php

namespace Tests\Feature;

use App\Enums\EnrollmentStatus;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseSchedule;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $teacher;
    protected User $otherTeacher;
    protected User $student;

    protected function setUp(): void
    {
        parent::setUp();

        // Initialize sample teacher and student users
        $this->teacher = User::factory()->create([
            'role' => User::ROLE_TEACHER,
            'email' => 'teacher@example.com',
        ]);

        $this->otherTeacher = User::factory()->create([
            'role' => User::ROLE_TEACHER,
            'email' => 'other_teacher@example.com',
        ]);

        $this->student = User::factory()->create([
            'role' => User::ROLE_STUDENT,
            'email' => 'student@example.com',
        ]);
    }

    /**
     * Test that unauthenticated guests cannot access the teacher dashboard.
     */
    public function test_guest_cannot_access_teacher_dashboard(): void
    {
        $response = $this->get(route('teacher.dashboard'));

        $response->assertRedirect(route('home'));
    }

    /**
     * Test that students cannot access the teacher dashboard (403 Forbidden).
     */
    public function test_student_cannot_access_teacher_dashboard(): void
    {
        $response = $this->actingAs($this->student)->get(route('teacher.dashboard'));

        $response->assertForbidden();
    }

    /**
     * Test that authenticated teacher can view the dashboard successfully.
     */
    public function test_teacher_can_view_dashboard_successfully(): void
    {
        $response = $this->actingAs($this->teacher)->get(route('teacher.dashboard'));

        $response->assertOk();
        $response->assertViewIs('portal.teacher.dashboard');
        $response->assertViewHasAll(['stats', 'schedules', 'notifications']);
    }

    /**
     * Test that summary statistics are calculated accurately on the dashboard.
     */
    public function test_dashboard_calculates_correct_summary_stats(): void
    {
        $category = Category::create([
            'name' => 'HSK Standard',
            'slug' => 'hsk-standard',
            'type' => Category::TYPE_COURSE,
        ]);

        // Create 2 courses for this teacher
        $course1 = Course::create([
            'teacher_id' => $this->teacher->id,
            'category_id' => $category->id,
            'title' => 'HSK 1 Basic',
            'slug' => 'hsk-1-basic',
        ]);

        $course2 = Course::create([
            'teacher_id' => $this->teacher->id,
            'category_id' => $category->id,
            'title' => 'HSK 2 Advanced',
            'slug' => 'hsk-2-advanced',
        ]);

        // Create 1 course for another teacher
        $otherCourse = Course::create([
            'teacher_id' => $this->otherTeacher->id,
            'category_id' => $category->id,
            'title' => 'Foreign Course',
            'slug' => 'foreign-course',
        ]);

        // Enroll students across courses
        $studentA = User::factory()->create(['role' => User::ROLE_STUDENT]);
        $studentB = User::factory()->create(['role' => User::ROLE_STUDENT]);
        $studentC = User::factory()->create(['role' => User::ROLE_STUDENT]);
        $studentD = User::factory()->create(['role' => User::ROLE_STUDENT]);

        Enrollment::create(['user_id' => $studentA->id, 'course_id' => $course1->id, 'status' => EnrollmentStatus::ACTIVE]);
        Enrollment::create(['user_id' => $studentB->id, 'course_id' => $course1->id, 'status' => EnrollmentStatus::ACTIVE]);
        Enrollment::create(['user_id' => $studentA->id, 'course_id' => $course2->id, 'status' => EnrollmentStatus::ACTIVE]);
        Enrollment::create(['user_id' => $studentC->id, 'course_id' => $course2->id, 'status' => EnrollmentStatus::ACTIVE]);
        Enrollment::create(['user_id' => $studentD->id, 'course_id' => $otherCourse->id, 'status' => EnrollmentStatus::ACTIVE]);

        // Create assignment with submissions
        $assignment = Assignment::create([
            'teacher_id' => $this->teacher->id,
            'course_id' => $course1->id,
            'title' => 'Translation Assignment',
            'due_date' => Carbon::now()->addDays(3),
            'max_score' => 10,
        ]);

        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'user_id' => $studentA->id,
            'status' => AssignmentSubmission::STATUS_SUBMITTED,
        ]);

        AssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'user_id' => $studentB->id,
            'status' => AssignmentSubmission::STATUS_GRADED,
            'score' => 9.5,
        ]);

        $response = $this->actingAs($this->teacher)->get(route('teacher.dashboard'));

        $response->assertOk();
        $stats = $response->viewData('stats');

        $this->assertEquals(2, $stats['total_courses']);
        $this->assertEquals(3, $stats['total_students']);
        $this->assertEquals(1, $stats['pending_assignments_count']);
    }

    /**
     * Test that today's teaching schedules are displayed on the dashboard.
     */
    public function test_dashboard_displays_today_schedules(): void
    {
        $todayDayOfWeek = Carbon::now()->dayOfWeek;

        $category = Category::create([
            'name' => 'Schedule Category',
            'slug' => 'schedule-category',
            'type' => Category::TYPE_COURSE,
        ]);

        $course = Course::create([
            'teacher_id' => $this->teacher->id,
            'category_id' => $category->id,
            'title' => 'Morning Class',
            'slug' => 'morning-class',
        ]);

        Lesson::create([
            'course_id' => $course->id,
            'title' => 'Lesson 1: Hello',
            'slug' => 'lesson-1-hello',
            'order' => 1,
        ]);

        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $course->id,
            'status' => EnrollmentStatus::ACTIVE,
        ]);

        CourseSchedule::create([
            'course_id' => $course->id,
            'day_of_week' => $todayDayOfWeek,
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'room' => 'Room 201',
        ]);

        $response = $this->actingAs($this->teacher)->get(route('teacher.dashboard'));

        $response->assertOk();
        $schedules = $response->viewData('schedules');

        $this->assertCount(1, $schedules);
        $this->assertEquals('Morning Class', $schedules->first()->course->title);
        $this->assertEquals(1, $schedules->first()->students_count);
        $this->assertEquals('Lesson 1: Hello', $schedules->first()->current_lesson_title);
    }

    /**
     * Test that a teacher cannot see schedules belonging to other teachers.
     */
    public function test_teacher_does_not_see_other_teachers_schedules(): void
    {
        $todayDayOfWeek = Carbon::now()->dayOfWeek;

        $category = Category::create([
            'name' => 'Private Category',
            'slug' => 'private-category',
            'type' => Category::TYPE_COURSE,
        ]);

        $otherCourse = Course::create([
            'teacher_id' => $this->otherTeacher->id,
            'category_id' => $category->id,
            'title' => 'Other Teacher Course',
            'slug' => 'other-teacher-course',
        ]);

        CourseSchedule::create([
            'course_id' => $otherCourse->id,
            'day_of_week' => $todayDayOfWeek,
            'start_time' => '14:00:00',
            'end_time' => '16:00:00',
            'room' => 'Room 303',
        ]);

        $response = $this->actingAs($this->teacher)->get(route('teacher.dashboard'));

        $response->assertOk();
        $schedules = $response->viewData('schedules');

        $this->assertCount(0, $schedules);
    }
}
