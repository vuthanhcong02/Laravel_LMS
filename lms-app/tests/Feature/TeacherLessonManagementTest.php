<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TeacherLessonManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $teacher;
    protected Course $course;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->teacher = User::factory()->create([
            'role' => User::ROLE_TEACHER,
        ]);

        $category = Category::create([
            'name' => 'HSK 2',
            'slug' => 'hsk-2',
            'type' => Category::TYPE_COURSE,
        ]);

        $this->course = Course::create([
            'teacher_id'   => $this->teacher->id,
            'category_id'  => $category->id,
            'title'        => 'Lớp HSK 2 Cấp Tốc',
            'slug'         => 'lop-hsk-2-cap-toc',
            'description'  => 'Mô tả lớp học HSK 2',
            'price'        => 600000,
            'is_published' => true,
        ]);
    }

    /**
     * Test teacher can create lesson with all resources (Record URL, PDF file, Note file, Text note).
     */
    public function test_teacher_can_create_lesson_with_all_resources(): void
    {
        $pdfFile = UploadedFile::fake()->create('slide_lesson_1.pdf', 1024, 'application/pdf');
        $noteFile = UploadedFile::fake()->create('notes_lesson_1.docx', 512, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

        $response = $this->actingAs($this->teacher)->post(route('teacher.classes.lessons.store', $this->course->id), [
            'title'        => 'Bài 1: Chào hỏi cơ bản',
            'description'  => 'Tập phát âm và hội thoại chào hỏi',
            'record_url'   => 'https://drive.google.com/file/d/12345/view',
            'pdf_file'     => $pdfFile,
            'note_file'    => $noteFile,
            'note_content' => 'Ghi chú quan trọng: Chú ý biến điệu thanh 3.',
        ]);

        $response->assertRedirect(route('teacher.classes.show', ['course' => $this->course->id, 'tab' => 'lessons']));

        $lesson = Lesson::where('course_id', $this->course->id)->first();
        $this->assertNotNull($lesson);
        $this->assertEquals('Bài 1: Chào hỏi cơ bản', $lesson->title);
        $this->assertEquals('https://drive.google.com/file/d/12345/view', $lesson->record_url);
        $this->assertEquals('Ghi chú quan trọng: Chú ý biến điệu thanh 3.', $lesson->note_content);
        $this->assertNotNull($lesson->pdf_path);
        $this->assertNotNull($lesson->note_file_path);

        Storage::disk('public')->assertExists($lesson->pdf_path);
        Storage::disk('public')->assertExists($lesson->note_file_path);
    }

    /**
     * Test teacher can update lesson and replace resource files.
     */
    public function test_teacher_can_update_lesson_resources(): void
    {
        $oldPdf = UploadedFile::fake()->create('old_doc.pdf', 500, 'application/pdf');
        $storedOldPath = $oldPdf->store('lessons/pdfs', 'public');

        $lesson = Lesson::create([
            'course_id'   => $this->course->id,
            'title'       => 'Bài học cũ',
            'record_url'  => 'https://drive.google.com/file/d/1l2wkiMBq6v5hzm4bm_Ktazy23LJ204ru/view',
            'pdf_path'    => $storedOldPath,
            'order'       => 1,
        ]);

        $newPdf = UploadedFile::fake()->create('new_slide.pdf', 800, 'application/pdf');

        $response = $this->actingAs($this->teacher)->put(route('teacher.classes.lessons.update', [
            'course' => $this->course->id,
            'lesson' => $lesson->id,
        ]), [
            'title'        => 'Bài học đã đổi tên',
            'record_url'   => 'https://drive.google.com/new_record',
            'pdf_file'     => $newPdf,
            'note_content' => 'Ghi chú mới',
        ]);

        $response->assertRedirect(route('teacher.classes.show', ['course' => $this->course->id, 'tab' => 'lessons']));

        $updatedLesson = $lesson->fresh();
        $this->assertEquals('Bài học đã đổi tên', $updatedLesson->title);
        $this->assertEquals('https://drive.google.com/new_record', $updatedLesson->record_url);
        $this->assertEquals('Ghi chú mới', $updatedLesson->note_content);
        
        // New PDF file must exist
        Storage::disk('public')->assertExists($updatedLesson->pdf_path);
        // Old PDF file must be deleted
        Storage::disk('public')->assertMissing($storedOldPath);
    }

    /**
     * Test teacher can delete lesson and clean up stored files automatically.
     */
    public function test_teacher_can_delete_lesson_and_clean_files(): void
    {
        $pdf = UploadedFile::fake()->create('to_delete.pdf', 300, 'application/pdf');
        $pdfPath = $pdf->store('lessons/pdfs', 'public');

        $note = UploadedFile::fake()->create('to_delete.txt', 100, 'text/plain');
        $notePath = $note->store('lessons/notes', 'public');

        $lesson = Lesson::create([
            'course_id'      => $this->course->id,
            'title'          => 'Bài học cần xóa',
            'pdf_path'       => $pdfPath,
            'note_file_path' => $notePath,
            'order'          => 1,
        ]);

        $response = $this->actingAs($this->teacher)->delete(route('teacher.classes.lessons.destroy', [
            'course' => $this->course->id,
            'lesson' => $lesson->id,
        ]));

        $response->assertRedirect(route('teacher.classes.show', ['course' => $this->course->id, 'tab' => 'lessons']));

        $this->assertDatabaseMissing('lessons', [
            'id' => $lesson->id,
        ]);

        // Verify storage files have been cleaned up
        Storage::disk('public')->assertMissing($pdfPath);
        Storage::disk('public')->assertMissing($notePath);
    }

    /**
     * Test teacher can reorder lessons (move up / move down).
     */
    public function test_teacher_can_reorder_lessons(): void
    {
        $lesson1 = Lesson::create([
            'course_id' => $this->course->id,
            'title'     => 'Bài 1',
            'order'     => 1,
        ]);

        $lesson2 = Lesson::create([
            'course_id' => $this->course->id,
            'title'     => 'Bài 2',
            'order'     => 2,
        ]);

        // Move lesson 2 up before lesson 1
        $response = $this->actingAs($this->teacher)->post(route('teacher.classes.lessons.move-up', [
            'course' => $this->course->id,
            'lesson' => $lesson2->id,
        ]));

        $response->assertRedirect(route('teacher.classes.show', ['course' => $this->course->id, 'tab' => 'lessons']));

        $this->assertEquals(2, $lesson1->fresh()->order);
        $this->assertEquals(1, $lesson2->fresh()->order);
    }

    /**
     * Test unauthorized teacher cannot manage lessons of foreign course.
     */
    public function test_other_teacher_cannot_manage_foreign_course_lessons(): void
    {
        $otherTeacher = User::factory()->create([
            'role' => User::ROLE_TEACHER,
        ]);

        $response = $this->actingAs($otherTeacher)->post(route('teacher.classes.lessons.store', $this->course->id), [
            'title' => 'Bài học gian lận',
        ]);

        $response->assertStatus(403);
    }
}
