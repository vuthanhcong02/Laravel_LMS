<?php

namespace Tests\Feature;

use App\Enums\EnrollmentStatus;
use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherClassEnrollmentTest extends TestCase
{
    use RefreshDatabase;

    protected User $teacher;
    protected Course $course;
    protected User $student1;
    protected User $student2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->teacher = User::factory()->create([
            'role' => User::ROLE_TEACHER,
        ]);

        $category = Category::create([
            'name' => 'HSK 1',
            'slug' => 'hsk-1',
            'type' => Category::TYPE_COURSE,
        ]);

        $this->course = Course::create([
            'teacher_id'   => $this->teacher->id,
            'category_id'  => $category->id,
            'title'        => 'Lớp HSK 1 Cơ Bản',
            'slug'         => 'lop-hsk-1-co-ban',
            'description'  => 'Mô tả khóa học cơ bản',
            'price'        => 500000,
            'is_published' => true,
        ]);

        $this->student1 = User::factory()->create([
            'role'       => User::ROLE_STUDENT,
            'first_name' => 'Nguyễn',
            'last_name'  => 'An',
            'email'      => 'an@gmail.com',
        ]);

        $this->student2 = User::factory()->create([
            'role'       => User::ROLE_STUDENT,
            'first_name' => 'Trần',
            'last_name'  => 'Bình',
            'email'      => 'binh@gmail.com',
        ]);
    }

    /**
     * Test teacher can view classes list.
     */
    public function test_teacher_can_view_classes_list(): void
    {
        $response = $this->actingAs($this->teacher)->get(route('teacher.classes.index'));

        $response->assertStatus(200);
        $response->assertSee('Lớp HSK 1 Cơ Bản');
    }

    /**
     * Test teacher can view class details.
     */
    public function test_teacher_can_view_class_details(): void
    {
        $this->withoutExceptionHandling();
        $response = $this->actingAs($this->teacher)->get(route('teacher.classes.show', $this->course->id));

        $response->assertStatus(200);
        $response->assertSee('Lớp HSK 1 Cơ Bản');
    }


    /**
     * Test API searching available students to enroll.
     */
    public function test_teacher_can_get_available_students_list(): void
    {
        $response = $this->actingAs($this->teacher)
            ->getJson(route('teacher.classes.available-students', ['course' => $this->course->id, 'q' => 'an']));

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id'    => $this->student1->id,
            'email' => 'an@gmail.com',
        ]);
    }

    /**
     * Test teacher can successfully enroll a single student.
     */
    public function test_teacher_can_enroll_student_to_class(): void
    {
        $response = $this->actingAs($this->teacher)->post(route('teacher.classes.enroll', $this->course->id), [
            'user_id' => $this->student1->id,
        ]);

        $response->assertRedirect(route('teacher.classes.show', ['course' => $this->course->id, 'tab' => 'students']));

        $this->assertDatabaseHas('enrollments', [
            'course_id' => $this->course->id,
            'user_id'   => $this->student1->id,
            'status'    => EnrollmentStatus::ACTIVE->value,
        ]);
    }

    /**
     * Test teacher can successfully enroll multiple students at once.
     */
    public function test_teacher_can_enroll_multiple_students_to_class(): void
    {
        $response = $this->actingAs($this->teacher)->post(route('teacher.classes.enroll', $this->course->id), [
            'user_ids' => [$this->student1->id, $this->student2->id],
        ]);

        $response->assertRedirect(route('teacher.classes.show', ['course' => $this->course->id, 'tab' => 'students']));

        $this->assertDatabaseHas('enrollments', [
            'course_id' => $this->course->id,
            'user_id'   => $this->student1->id,
            'status'    => EnrollmentStatus::ACTIVE->value,
        ]);

        $this->assertDatabaseHas('enrollments', [
            'course_id' => $this->course->id,
            'user_id'   => $this->student2->id,
            'status'    => EnrollmentStatus::ACTIVE->value,
        ]);
    }

    /**
     * Test teacher can unenroll a student from class.
     */
    public function test_teacher_can_unenroll_student_from_class(): void
    {
        $enrollment = Enrollment::create([
            'course_id' => $this->course->id,
            'user_id'   => $this->student1->id,
            'status'    => EnrollmentStatus::ACTIVE,
        ]);

        $response = $this->actingAs($this->teacher)->delete(route('teacher.classes.unenroll', [
            'course'     => $this->course->id,
            'enrollment' => $enrollment->id,
        ]));

        $response->assertRedirect(route('teacher.classes.show', ['course' => $this->course->id, 'tab' => 'students']));

        $this->assertDatabaseMissing('enrollments', [
            'id' => $enrollment->id,
        ]);
    }

    /**
     * Test unauthorized teacher cannot manage another teacher class.
     */
    public function test_other_teacher_cannot_manage_foreign_class(): void
    {
        $otherTeacher = User::factory()->create([
            'role' => User::ROLE_TEACHER,
        ]);

        $response = $this->actingAs($otherTeacher)->get(route('teacher.classes.show', $this->course->id));
        $response->assertStatus(403);

        $enrollResponse = $this->actingAs($otherTeacher)->post(route('teacher.classes.enroll', $this->course->id), [
            'user_id' => $this->student1->id,
        ]);
        $enrollResponse->assertStatus(403);
    }
}
