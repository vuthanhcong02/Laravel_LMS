<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Database\Seeder;

class AssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teacher = User::where('email', 'teacher@lms.com')->first();
        if (!$teacher) {
            $this->command->error('Teacher teacher@lms.com not found. Please run TeacherPortalSeeder first.');
            return;
        }

        $courses = Course::where('teacher_id', $teacher->id)->with('lessons', 'enrollments')->get();
        if ($courses->isEmpty()) {
            $this->command->error('No courses found for this teacher.');
            return;
        }

        foreach ($courses as $course) {
            // 1. Create a general course assignment
            $assignment1 = Assignment::create([
                'course_id' => $course->id,
                'teacher_id' => $teacher->id,
                'title' => 'Bài tập tổng hợp: ' . $course->title,
                'description' => 'Hãy viết một đoạn văn ngắn (150-200 chữ) về chủ đề đã học trong khóa học này.',
                'status' => Assignment::STATUS_PUBLISHED,
                'due_date' => now()->addDays(7),
                'attachments' => [
                    ['name' => 'huong-dan-lam-bai.pdf', 'path' => 'assignments/seed_guide.pdf']
                ],
            ]);

            // 2. Create a lesson-specific assignment
            $lesson = $course->lessons->first();
            if ($lesson) {
                $assignment2 = Assignment::create([
                    'course_id' => $course->id,
                    'lesson_id' => $lesson->id,
                    'teacher_id' => $teacher->id,
                    'title' => 'Bài tập thực hành: ' . $lesson->title,
                    'description' => 'Hoàn thành các bài tập trong file đính kèm sau khi xem xong video bài học.',
                    'status' => Assignment::STATUS_PUBLISHED,
                    'due_date' => now()->addDays(3),
                    'attachments' => [
                        ['name' => 'bai-tap-thuc-hanh.docx', 'path' => 'assignments/seed_lesson_task.docx']
                    ],
                ]);
            }

            // 3. Create a draft assignment
            Assignment::create([
                'course_id' => $course->id,
                'teacher_id' => $teacher->id,
                'title' => '[Nháp] Kiểm tra giữa kỳ',
                'description' => 'Nội dung đang được biên soạn...',
                'status' => Assignment::STATUS_DRAFT,
                'due_date' => now()->addDays(14),
            ]);

            // 4. Create sample submissions for assignment1
            $students = $course->enrollments->take(5); // Get first 5 students
            foreach ($students as $enrollment) {
                $student = $enrollment->user;
                
                // Random status for submissions
                $status = rand(1, 2); // Submitted or Graded
                
                AssignmentSubmission::create([
                    'assignment_id' => $assignment1->id,
                    'user_id' => $student->id,
                    'status' => $status,
                    'attachments' => [
                        ['name' => 'bai-lam-cua-' . $student->first_name . '.pdf', 'path' => 'submissions/seed_submission.pdf']
                    ],
                    'score' => $status === AssignmentSubmission::STATUS_GRADED ? rand(70, 100) : null,
                    'teacher_feedback' => $status === AssignmentSubmission::STATUS_GRADED ? 'Bài làm rất tốt, cần chú ý thêm về ngữ pháp.' : null,
                ]);
            }
        }

        $this->command->info('Assignment module seeded successfully!');
    }
}
