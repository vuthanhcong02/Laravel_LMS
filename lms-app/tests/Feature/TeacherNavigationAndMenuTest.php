<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TeacherNavigationAndMenuTest extends TestCase
{
    use RefreshDatabase;

    protected User $teacher;
    protected User $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->teacher = User::factory()->create([
            'role' => User::ROLE_TEACHER,
            'first_name' => 'John',
            'last_name' => 'Teacher',
            'email' => 'teacher@example.com',
            'password' => Hash::make('Password@123'),
        ]);

        $this->student = User::factory()->create([
            'role' => User::ROLE_STUDENT,
            'email' => 'student@example.com',
        ]);
    }

    /**
     * Test that teacher can access all main sidebar menu pages.
     */
    public function test_teacher_can_access_all_sidebar_menu_pages(): void
    {
        $category = Category::create([
            'name' => 'Sample Category',
            'slug' => 'sample-category',
            'type' => Category::TYPE_COURSE,
        ]);

        Course::create([
            'teacher_id' => $this->teacher->id,
            'category_id' => $category->id,
            'title' => 'Test Class',
            'slug' => 'test-class',
        ]);

        $routes = [
            'teacher.dashboard',
            'teacher.classes.index',
            'teacher.assignments.index',
            'teacher.quizzes.index',
            'teacher.reports.index',
            'teacher.schedules.index',
            'teacher.profile.edit',
        ];

        foreach ($routes as $routeName) {
            $response = $this->actingAs($this->teacher)->get(route($routeName));
            $response->assertOk();
        }
    }

    /**
     * Test that teacher can update profile information.
     */
    public function test_teacher_can_update_profile_info(): void
    {
        $response = $this->actingAs($this->teacher)->put(route('teacher.profile.update'), [
            'first_name' => 'Jane',
            'last_name' => 'Teacher',
            'email' => 'teacher_updated@example.com',
        ]);

        $response->assertRedirect(route('teacher.profile.edit'));
        $this->assertDatabaseHas('users', [
            'id' => $this->teacher->id,
            'first_name' => 'Jane',
            'last_name' => 'Teacher',
            'email' => 'teacher_updated@example.com',
        ]);
    }

    /**
     * Test that teacher can update password after validating current password.
     */
    public function test_teacher_can_update_password_with_valid_current_password(): void
    {
        // 1. Invalid current password should trigger validation error
        $responseFail = $this->actingAs($this->teacher)->put(route('teacher.profile.updatePassword'), [
            'current_password' => 'WrongPassword',
            'password' => 'NewSecurePassword@123',
            'password_confirmation' => 'NewSecurePassword@123',
        ]);

        $responseFail->assertSessionHasErrors('current_password');

        // 2. Valid current password should update successfully
        $responseSuccess = $this->actingAs($this->teacher)->put(route('teacher.profile.updatePassword'), [
            'current_password' => 'Password@123',
            'password' => 'NewSecurePassword@123',
            'password_confirmation' => 'NewSecurePassword@123',
        ]);

        $responseSuccess->assertRedirect(route('teacher.profile.edit'));
        $this->teacher->refresh();
        $this->assertTrue(Hash::check('NewSecurePassword@123', $this->teacher->password));
    }
}
