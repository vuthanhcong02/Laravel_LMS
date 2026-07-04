<?php

namespace Database\Seeders;

use App\Models\HskLevel;
use Illuminate\Database\Seeder;

class HskLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            [
                'level_code' => 'hsk1',
                'title' => 'HSK 1',
                'subtitle' => 'Khởi đầu Hán ngữ',
                'description' => 'Dành cho người mới bắt đầu học tiếng Trung, làm quen với Pinyin, các nét viết Hán tự và các mẫu câu giao tiếp cơ bản nhất.',
                'color' => 'emerald',
                'lessons_count' => 15,
                'vocab_count' => 150,
                'duration' => '30 giờ',
                'spine_color' => 'bg-[#eab308]',
                'cover_bg' => 'bg-[#fffdf5] border-[#fef08a]',
                'number_color' => 'text-[#eab308]'
            ],
            [
                'level_code' => 'hsk2',
                'title' => 'HSK 2',
                'subtitle' => 'Sơ cấp Hán ngữ',
                'description' => 'Nâng cao khả năng hội thoại hàng ngày, mua sắm, hỏi đường, đặt đồ ăn và các tình huống giao tiếp cơ bản.',
                'color' => 'cyan',
                'lessons_count' => 15,
                'vocab_count' => 300,
                'duration' => '45 giờ',
                'spine_color' => 'bg-[#0f766e]',
                'cover_bg' => 'bg-[#eefcfb] border-[#d6f5f3]',
                'number_color' => 'text-[#0f766e]'
            ],
            [
                'level_code' => 'hsk3',
                'title' => 'HSK 3',
                'subtitle' => 'Trung cấp Hán ngữ',
                'description' => 'Luyện tập giao tiếp tự tin về các chủ đề đời sống, học tập, công việc. Đủ khả năng du lịch tự túc tại Trung Quốc.',
                'color' => 'blue',
                'lessons_count' => 20,
                'vocab_count' => 600,
                'duration' => '60 giờ',
                'spine_color' => 'bg-[#dc2626]',
                'cover_bg' => 'bg-[#fff5f5] border-[#fecaca]',
                'number_color' => 'text-[#dc2626]'
            ],
            [
                'level_code' => 'hsk4',
                'title' => 'HSK 4',
                'subtitle' => 'Trung - Cao cấp',
                'description' => 'Bàn luận lưu loát về nhiều chủ đề phong phú, có thể hội thoại trôi chảy với người bản xứ với tốc độ tự nhiên.',
                'color' => 'purple',
                'lessons_count' => 20,
                'vocab_count' => 1200,
                'duration' => '90 giờ',
                'spine_color' => 'bg-[#6d28d9]',
                'cover_bg' => 'bg-[#faf5ff] border-[#f3e8ff]',
                'number_color' => 'text-[#6d28d9]'
            ],
            [
                'level_code' => 'hsk5',
                'title' => 'HSK 5',
                'subtitle' => 'Cao cấp Hán ngữ',
                'description' => 'Đọc hiểu báo chí, xem phim truyền hình tiếng Trung không cần sub, thuyết trình tự tin trước đám đông.',
                'color' => 'rose',
                'lessons_count' => 36,
                'vocab_count' => 2500,
                'duration' => '120 giờ',
                'spine_color' => 'bg-[#be185d]',
                'cover_bg' => 'bg-[#fff5f7] border-[#ffe4e6]',
                'number_color' => 'text-[#be185d]'
            ],
            [
                'level_code' => 'hsk6',
                'title' => 'HSK 6',
                'subtitle' => 'Thượng thừa Hán ngữ',
                'description' => 'Đạt trình độ cận bản xứ, dễ dàng hiểu được bất kỳ thông tin nào bằng tiếng Trung, viết luận và dịch thuật chuyên nghiệp.',
                'color' => 'amber',
                'lessons_count' => 40,
                'vocab_count' => 5000,
                'duration' => '180 giờ',
                'spine_color' => 'bg-[#1d4ed8]',
                'cover_bg' => 'bg-[#eff6ff] border-[#dbeafe]',
                'number_color' => 'text-[#1d4ed8]'
            ]
        ];

        foreach ($levels as $level) {
            HskLevel::updateOrCreate(
                ['level_code' => $level['level_code']],
                $level
            );
        }
    }
}
