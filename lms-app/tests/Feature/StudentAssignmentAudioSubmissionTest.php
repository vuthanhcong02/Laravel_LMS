<?php

namespace Tests\Feature;

use App\Enums\EnrollmentStatus;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudentAssignmentAudioSubmissionTest extends TestCase
{
    use DatabaseTransactions;

    public function test_student_can_submit_assignment_with_audio_recording()
    {
        Storage::fake('local');

        $student = User::factory()->create(['role' => User::ROLE_STUDENT, 'exp_total' => 0]);
        $teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $course = Course::factory()->create(['teacher_id' => $teacher->id]);

        Enrollment::create([
            'user_id'   => $student->id,
            'course_id' => $course->id,
            'status'    => EnrollmentStatus::ACTIVE,
        ]);

        $assignment = Assignment::create([
            'course_id'   => $course->id,
            'teacher_id'  => $teacher->id,
            'title'       => 'Bài tập phát âm HSK 1',
            'description' => 'Ghi âm phát âm 5 từ vựng',
            'status'      => Assignment::STATUS_PUBLISHED,
        ]);

        $audioFile = UploadedFile::fake()->create('recording_12345.webm', 150, 'audio/webm');

        $response = $this->actingAs($student)->post(route('student.assignments.submit', $assignment->id), [
            'audio_file' => $audioFile,
        ]);

        $response->assertSessionHas('success');
        $response->assertRedirect();

        $this->assertDatabaseHas('assignment_submissions', [
            'assignment_id' => $assignment->id,
            'user_id'       => $student->id,
            'status'        => AssignmentSubmission::STATUS_SUBMITTED,
        ]);

        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('user_id', $student->id)
            ->first();

        $this->assertNotEmpty($submission->attachments);
        $this->assertEquals('recording_12345.webm', $submission->attachments[0]['name']);
        Storage::disk('local')->assertExists($submission->attachments[0]['path']);

        $student->refresh();
        $this->assertEquals(25, $student->exp_total);
    }
}
