<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseSchedule;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCourseScheduleTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $teacher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $this->teacher = User::factory()->create([
            'role' => User::ROLE_TEACHER,
        ]);
    }

    /**
     * Test admin can update course with full schedule.
     */
    public function test_admin_can_update_course_with_valid_schedule(): void
    {
        $course = Course::factory()->create([
            'teacher_id' => $this->teacher->id,
            'title' => 'Khóa Học Tiếng Trung HSK 1',
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.courses.update', $course), [
            'title' => 'Khóa Học Tiếng Trung HSK 1 Đã Cập Nhật',
            'teacher_id' => $this->teacher->id,
            'is_published' => 1,
            'start_date' => '2026-10-10',
            'end_date' => '2026-12-30',
            'start_time' => '19:30',
            'end_time' => '21:00',
            'days_of_week' => [1, 3, 5], // Thứ 2, 4, 6
        ]);

        $response->assertRedirect(route('admin.courses.edit', $course));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'title' => 'Khóa Học Tiếng Trung HSK 1 Đã Cập Nhật',
            'start_date' => '2026-10-10 00:00:00',
            'end_date' => '2026-12-30 00:00:00',
        ]);

        $this->assertEquals(3, CourseSchedule::where('course_id', $course->id)->count());
        $this->assertDatabaseHas('course_schedules', [
            'course_id' => $course->id,
            'day_of_week' => 1,
            'start_time' => '19:30:00',
            'end_time' => '21:00:00',
        ]);
    }

    /**
     * Test admin can update schedule without start/end date.
     */
    public function test_admin_can_update_schedule_without_start_or_end_date(): void
    {
        $course = Course::factory()->create([
            'teacher_id' => $this->teacher->id,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.courses.update', $course), [
            'title' => $course->title,
            'teacher_id' => $this->teacher->id,
            'is_published' => 1,
            'start_time' => '18:00',
            'end_time' => '19:30',
            'days_of_week' => [2, 4], // Thứ 3, 5
        ]);

        $response->assertRedirect(route('admin.courses.edit', $course));
        $response->assertSessionHas('success');

        $this->assertEquals(2, CourseSchedule::where('course_id', $course->id)->count());
    }

    /**
     * Test admin can clear schedules by leaving time and days empty.
     */
    public function test_admin_can_clear_schedules(): void
    {
        $course = Course::factory()->create([
            'teacher_id' => $this->teacher->id,
        ]);

        CourseSchedule::create([
            'course_id' => $course->id,
            'day_of_week' => 1,
            'start_time' => '19:00:00',
            'end_time' => '21:00:00',
        ]);

        $this->assertEquals(1, CourseSchedule::where('course_id', $course->id)->count());

        $response = $this->actingAs($this->admin)->put(route('admin.courses.update', $course), [
            'title' => $course->title,
            'is_published' => 1,
            'start_time' => null,
            'end_time' => null,
            'days_of_week' => null,
        ]);

        $response->assertRedirect(route('admin.courses.edit', $course));
        $this->assertEquals(0, CourseSchedule::where('course_id', $course->id)->count());
    }

    /**
     * Test validation detects overlapping schedule for same teacher.
     */
    public function test_schedule_overlap_validation(): void
    {
        $course1 = Course::factory()->create([
            'teacher_id' => $this->teacher->id,
            'start_date' => '2026-10-01',
            'end_date' => '2026-12-31',
        ]);

        CourseSchedule::create([
            'course_id' => $course1->id,
            'day_of_week' => 1, // Thứ 2
            'start_time' => '19:00:00',
            'end_time' => '21:00:00',
        ]);

        $course2 = Course::factory()->create([
            'teacher_id' => $this->teacher->id,
        ]);

        // Intentionally assign overlapping time slot 19:30 - 20:30 on Monday for the same teacher
        $response = $this->actingAs($this->admin)->put(route('admin.courses.update', $course2), [
            'title' => $course2->title,
            'teacher_id' => $this->teacher->id,
            'is_published' => 1,
            'start_date' => '2026-10-01',
            'end_date' => '2026-12-31',
            'start_time' => '19:30',
            'end_time' => '20:30',
            'days_of_week' => [1],
        ]);

        $response->assertSessionHasErrors('start_time');
    }
}
