<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class Radicals214Data
{
    /**
     * Lấy toàn bộ 214 Bộ thủ Khang Hy phân nhóm theo số nét (1 - 17 nét) từ tệp dữ liệu JSON
     *
     * @return array<int, array{name: string, count: int, items: array}>
     */
    public static function getAll(): array
    {
        $path = database_path('data/hanzi/radicals_214.json');

        if (!File::exists($path)) {
            return [];
        }

        $list = json_decode(File::get($path), true) ?: [];
        $grouped = [];

        foreach ($list as $item) {
            $stroke = (int) ($item['stroke_count'] ?? 1);
            if (!isset($grouped[$stroke])) {
                $grouped[$stroke] = [
                    'name' => "{$stroke} nét",
                    'count' => 0,
                    'items' => [],
                ];
            }

            $grouped[$stroke]['items'][] = $item;
            $grouped[$stroke]['count']++;
        }

        ksort($grouped);

        return $grouped;
    }

    /**
     * Lấy toàn bộ Chữ Hán phân nhóm theo 6 cấp độ HSK từ tệp JSON characters_dictionary.json
     *
     * @return array<int, array{level: int, name: string, level_name: string, desc: string, color: string, count: int, items: array}>
     */
    public static function getHskAll(): array
    {
        $path = database_path('data/hanzi/characters_dictionary.json');

        $hskMeta = [
            1 => ['name' => 'HSK 1', 'level_name' => 'Sơ cấp 1', 'desc' => 'Hán tự căn bản và thông dụng nhất cho người mới bắt đầu', 'color' => '#10b981', 'badge_bg' => '#ecfdf5', 'badge_text' => '#059669', 'badge_border' => '#a7f3d0'],
            2 => ['name' => 'HSK 2', 'level_name' => 'Sơ cấp 2', 'desc' => 'Giao tiếp hàng ngày, mua sắm và hỏi đường thông dụng', 'color' => '#06b6d4', 'badge_bg' => '#ecfeff', 'badge_text' => '#0891b2', 'badge_border' => '#a5f3fc'],
            3 => ['name' => 'HSK 3', 'level_name' => 'Trung cấp 1', 'desc' => 'Đọc hiểu đoạn văn, diễn đạt cảm xúc và ý kiến cá nhân', 'color' => '#3b82f6', 'badge_bg' => '#eff6ff', 'badge_text' => '#2563eb', 'badge_border' => '#bfdbfe'],
            4 => ['name' => 'HSK 4', 'level_name' => 'Trung cấp 2', 'desc' => 'Thảo luận lưu loát nhiều chủ đề đời sống và công việc', 'color' => '#8b5cf6', 'badge_bg' => '#f5f3ff', 'badge_text' => '#7c3aed', 'badge_border' => '#ddd6fe'],
            5 => ['name' => 'HSK 5', 'level_name' => 'Cao cấp 1', 'desc' => 'Đọc báo chí, xem phim ảnh và thuyết trình chuyên môn', 'color' => '#f59e0b', 'badge_bg' => '#fffbeb', 'badge_text' => '#d97706', 'badge_border' => '#fde68a'],
            6 => ['name' => 'HSK 6', 'level_name' => 'Cao cấp 2', 'desc' => 'Diễn đạt phong phú tự nhiên, sử dụng thành ngữ chuẩn người bản xứ', 'color' => '#e07a5f', 'badge_bg' => '#fff2ee', 'badge_text' => '#c86349', 'badge_border' => '#fcdccf'],
        ];

        $grouped = [];
        for ($i = 1; $i <= 6; $i++) {
            $meta = $hskMeta[$i];
            $grouped[$i] = [
                'level' => $i,
                'name' => $meta['name'],
                'level_name' => $meta['level_name'],
                'desc' => $meta['desc'],
                'color' => $meta['color'],
                'badge_bg' => $meta['badge_bg'],
                'badge_text' => $meta['badge_text'],
                'badge_border' => $meta['badge_border'],
                'count' => 0,
                'items' => [],
            ];
        }

        if (!File::exists($path)) {
            return $grouped;
        }

        $list = json_decode(File::get($path), true) ?: [];

        foreach ($list as $c) {
            $hsk = (int) ($c['hsk_level'] ?? 1);
            if ($hsk < 1 || $hsk > 6) {
                $hsk = 1;
            }

            $grouped[$hsk]['items'][] = [
                'char' => $c['character'] ?? '',
                'pinyin' => $c['pinyin'] ?? '',
                'sino_vietnamese' => $c['sino_vietnamese'] ?? '',
                'meaning' => $c['meaning'] ?? '',
                'stroke_count' => (int) ($c['stroke_count'] ?? 1),
                'tone' => $c['tone'] ?? '1',
                'hsk' => $hsk,
                'radical_id' => $c['radical_code'] ?? '',
                'radical' => $c['radical'] ?? '',
                'radical_name' => $c['radical_name'] ?? '',
            ];
            $grouped[$hsk]['count']++;
        }

        return $grouped;
    }
}
