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

    public function test_student_can_submit_audio_and_files_simultaneously_and_retain_existing_files()
    {
        Storage::fake('local');

        $student = User::factory()->create(['role' => User::ROLE_STUDENT]);
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
            'title'       => 'Bài tập kết hợp audio và ảnh',
            'description' => 'Nộp cả ghi âm và ảnh',
            'status'      => Assignment::STATUS_PUBLISHED,
        ]);

        $audioFile = UploadedFile::fake()->create('recording_first.webm', 100, 'audio/webm');
        $imgFile = UploadedFile::fake()->image('homework.png');

        // Submit both simultaneously
        $response = $this->actingAs($student)->post(route('student.assignments.submit', $assignment->id), [
            'audio_file'  => $audioFile,
            'attachments' => [$imgFile],
        ]);

        $response->assertSessionHas('success');

        $submission = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('user_id', $student->id)
            ->first();

        $this->assertCount(2, $submission->attachments);

        $savedImgPath = $submission->attachments[0]['path'];
        $savedAudioPath = $submission->attachments[1]['path'];

        // Resubmit: retain the existing image, remove the audio, and upload a new audio
        $newAudioFile = UploadedFile::fake()->create('recording_new.webm', 100, 'audio/webm');
        $response = $this->actingAs($student)->post(route('student.assignments.submit', $assignment->id), [
            'audio_file'           => $newAudioFile,
            'existing_attachments' => [$savedImgPath], // retain image only
        ]);

        $response->assertSessionHas('success');
        $submission->refresh();

        $this->assertCount(2, $submission->attachments);
        $names = collect($submission->attachments)->pluck('name')->all();
        $this->assertContains('homework.png', $names);
        $this->assertContains('recording_new.webm', $names);
        $this->assertNotContains('recording_first.webm', $names);
    }
}
