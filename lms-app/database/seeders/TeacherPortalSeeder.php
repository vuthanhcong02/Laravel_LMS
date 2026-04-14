<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TeacherPortalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teacher = User::updateOrCreate(
            ['email' => 'teacher@lms.com'],
            [
                'first_name' => 'Nguyễn Thị',
                'last_name' => 'Giáo Viên',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'role' => User::ROLE_TEACHER,
                'avatar' => null,
            ]
        );

        $categories = [
            ['name' => 'Tiếng Trung HSK', 'slug' => 'hsk'],
            ['name' => 'Tiếng Trung Giao tiếp', 'slug' => 'giao-tiep'],
            ['name' => 'Tiếng Trung Thương mại', 'slug' => 'thuong-mai'],
            ['name' => 'Luyện thi TOCFL', 'slug' => 'tocfl'],
            ['name' => 'Tiếng Trung Trẻ em', 'slug' => 'tre-em'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], [
                'name' => $cat['name'],
                'type' => Category::TYPE_COURSE
            ]);
        }

        $allCats = Category::whereIn('slug', array_column($categories, 'slug'))->get();

        $courseTitles = [
            'Khóa học Tiếng Trung HSK 4 Cấp tốc',
            'Giao tiếp Tiếng Trung trong Công sở',
            'Tiếng Trung cho người mới bắt đầu (A1)',
            'Luyện dịch báo chí Trung - Việt',
            'Học tiếng Trung qua bài hát & Phim'
        ];

        foreach ($courseTitles as $index => $title) {
            $course = Course::create([
                'teacher_id' => $teacher->id,
                'category_id' => $allCats->random()->id,
                'title' => $title,
                'slug' => Str::slug($title) . '-' . Str::random(4),
                'description' => 'Khóa học chuyên sâu về ' . $title . '. Cung cấp đầy đủ kiến thức từ cơ bản đến nâng cao, giúp học viên làm chủ ngôn ngữ trong thời gian ngắn nhất.',
                'thumbnail' => null,
                'price' => rand(500000, 2000000),
                'is_published' => true,
            ]);

            for ($i = 1; $i <= 12; $i++) {
                Lesson::create([
                    'course_id' => $course->id,
                    'title' => "Bài $i: " . $this->getLessonTitle($i),
                    'description' => "Nội dung chi tiết bài học số $i của khóa $title.",
                    'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                    'order' => $i,
                ]);
            }

            $studentsCount = rand(15, 30);
            $students = User::factory($studentsCount)->create([
                'role' => User::ROLE_STUDENT
            ]);

            foreach ($students as $student) {
                Enrollment::create([
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                    'status' => 1,
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }

    private function getLessonTitle($index) {
        $titles = [
            'Làm quen và Chào hỏi',
            'Số đếm và Thời gian',
            'Đi siêu thị mua sắm',
            'Hỏi đường và Phương tiện',
            'Đặt phòng khách sạn',
            'Phòng vấn xin việc',
            'Giao tiếp tại bàn tiệc',
            'Sở thích và Giải trí',
            'Gia đình và Người thân',
            'Luyện nghe chuyên sâu',
            'Ôn tập kiến thức tổng hợp',
            'Kiểm tra cuối khóa'
        ];
        return $titles[$index - 1] ?? "Nội dung bài học $index";
    }
}
