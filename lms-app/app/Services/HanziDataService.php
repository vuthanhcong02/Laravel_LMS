<?php

namespace App\Services;

use App\Models\HanziCharacter;
use App\Models\HanziRadical;
use Illuminate\Support\Facades\Cache;

class HanziDataService
{
    protected HanziStrokeStorageService $strokeStorage;

    public function __construct(HanziStrokeStorageService $strokeStorage)
    {
        $this->strokeStorage = $strokeStorage;
    }

    /**
     * Lấy toàn bộ 214 Bộ thủ Khang Hy phân nhóm theo số nét (Có Cache)
     */
    public function getRadicals214Grouped(): array
    {
        return Cache::remember('hanzi:radicals:214:grouped', 86400, function () {
            $radicals = HanziRadical::active()
                ->ordered()
                ->with(['characters' => function ($query) {
                    $query->published()->orderBy('stroke_count', 'asc')->orderBy('popularity_rank', 'asc');
                }])
                ->get();

            $grouped = [];

            foreach ($radicals as $r) {
                $stroke = (int) $r->stroke_count;
                if (!isset($grouped[$stroke])) {
                    $grouped[$stroke] = [
                        'name' => "{$stroke} nét",
                        'count' => 0,
                        'items' => [],
                    ];
                }

                $charactersList = $r->characters->map(function ($c) {
                    return [
                        'char' => $c->character,
                        'pinyin' => $c->pinyin,
                        'sino_vietnamese' => $c->sino_vietnamese,
                        'meaning' => $c->meaning,
                        'stroke_count' => (int) $c->stroke_count,
                        'tone' => $c->tone,
                        'hsk' => (int) $c->hsk_level,
                    ];
                })->toArray();

                $grouped[$stroke]['items'][] = [
                    'id' => $r->code,
                    'radical' => $r->radical,
                    'original' => $r->original,
                    'sino_vietnamese' => $r->sino_vietnamese,
                    'pinyin' => $r->pinyin,
                    'stroke_count' => (int) $r->stroke_count,
                    'tone' => $r->tone,
                    'char_count' => (int) ($r->char_count ?: count($charactersList)),
                    'meaning' => $r->meaning,
                    'description' => $r->description,
                    'word_type' => $r->word_type,
                    'word_type_desc' => $r->word_type_desc,
                    'characters' => $charactersList,
                ];

                $grouped[$stroke]['count']++;
            }

            return $grouped;
        });
    }

    /**
     * Lấy toàn bộ Chữ Hán phân nhóm theo 6 cấp độ HSK 1 - HSK 6 (Có Cache)
     */
    public function getHskGrouped(): array
    {
        return Cache::remember('hanzi:hsk:grouped', 86400, function () {
            $characters = HanziCharacter::published()
                ->with('radical')
                ->orderBy('hsk_level', 'asc')
                ->orderBy('stroke_count', 'asc')
                ->orderBy('popularity_rank', 'asc')
                ->get();

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

            foreach ($characters as $c) {
                $hsk = (int) $c->hsk_level;
                if ($hsk < 1 || $hsk > 6) {
                    $hsk = 1;
                }

                $grouped[$hsk]['items'][] = [
                    'char' => $c->character,
                    'pinyin' => $c->pinyin,
                    'sino_vietnamese' => $c->sino_vietnamese,
                    'meaning' => $c->meaning,
                    'stroke_count' => (int) $c->stroke_count,
                    'tone' => $c->tone,
                    'hsk' => $hsk,
                    'radical_id' => $c->radical->code ?? '',
                    'radical' => $c->radical->radical ?? '',
                    'radical_name' => $c->radical->sino_vietnamese ?? '',
                ];
                $grouped[$hsk]['count']++;
            }

            return $grouped;
        });
    }

    /**
     * Lấy chi tiết đầy đủ của một chữ Hán (bao gồm Chiết tự, Nét vẽ, Thành phần, Từ ghép, Ví dụ)
     */
    public function getCharacterDetail(string $character): ?array
    {
        $cacheKey = "hanzi:char:detail:" . md5($character);

        return Cache::remember($cacheKey, 86400, function () use ($character) {
            $charModel = HanziCharacter::published()
                ->where('character', $character)
                ->with(['radical', 'components', 'compoundWords', 'examples'])
                ->first();

            if (!$charModel) {
                return null;
            }

            // Lấy vector strokes từ file lưu trữ phân cấp
            $strokeVectorData = $this->strokeStorage->getStrokeData($character, $charModel->stroke_file_path);

            return [
                'character' => $charModel->character,
                'unicode' => $charModel->unicode_hex,
                'pinyin' => $charModel->pinyin,
                'sino_vietnamese' => $charModel->sino_vietnamese,
                'meaning' => $charModel->meaning,
                'hsk_level' => (int) $charModel->hsk_level,
                'stroke_count' => (int) $charModel->stroke_count,
                'tone' => $charModel->tone,
                'word_types' => $charModel->word_types ?? [],
                'formation_type' => $charModel->formation_type,
                'radical_relation' => $charModel->radical_relation,
                'formation_desc' => $charModel->formation_desc,
                'mnemonic_tip' => $charModel->mnemonic_tip,
                'sino_vietnamese_mirror' => $charModel->sino_vietnamese_mirror,
                'mnemonic_poem' => (str_contains($charModel->mnemonic_poem ?? '', 'sáng tựa trăng sao') || str_contains($charModel->mnemonic_poem ?? '', 'đẹp tựa ngàn hoa')) ? null : $charModel->mnemonic_poem,
                'traditional_quote' => $charModel->traditional_quote,
                'naming_significance' => $charModel->naming_significance,
                'radical' => [
                    'id' => $charModel->radical->code,
                    'radical' => $charModel->radical->radical,
                    'sino_vietnamese' => $charModel->radical->sino_vietnamese,
                    'pinyin' => $charModel->radical->pinyin,
                    'meaning' => $charModel->radical->meaning,
                ],
                'components' => $charModel->components->map(fn($comp) => [
                    'character' => $comp->component_char,
                    'name' => $comp->component_name,
                    'role' => $comp->role,
                    'meaning' => $comp->meaning,
                    'explanation' => $comp->explanation,
                ])->toArray(),
                'compound_words' => $charModel->compoundWords->map(fn($w) => [
                    'word' => $w->word,
                    'pinyin' => $w->pinyin,
                    'sino_vietnamese' => $w->sino_vietnamese,
                    'meaning' => $w->meaning,
                    'hsk' => (int) $w->hsk_level,
                ])->toArray(),
                'examples' => $charModel->examples->map(fn($ex) => [
                    'chinese' => $ex->sentence_zh,
                    'pinyin' => $ex->sentence_pinyin,
                    'vietnamese' => $ex->sentence_vi,
                ])->toArray(),
                'stroke_data' => $strokeVectorData,
            ];
        });
    }

    /**
     * Tìm kiếm Chữ Hán & Bộ thủ
     */
    public function search(string $keyword): array
    {
        $keyword = trim($keyword);
        if (empty($keyword)) {
            return [];
        }

        $results = HanziCharacter::published()
            ->where(function ($query) use ($keyword) {
                $query->where('character', 'like', "%{$keyword}%")
                    ->orWhere('pinyin', 'like', "%{$keyword}%")
                    ->orWhere('sino_vietnamese', 'like', "%{$keyword}%")
                    ->orWhere('meaning', 'like', "%{$keyword}%");
            })
            ->with('radical')
            ->limit(30)
            ->get();

        return $results->map(function ($c) {
            return [
                'character' => $c->character,
                'pinyin' => $c->pinyin,
                'sino_vietnamese' => $c->sino_vietnamese,
                'meaning' => $c->meaning,
                'hsk_level' => (int) $c->hsk_level,
                'stroke_count' => (int) $c->stroke_count,
                'radical_id' => $c->radical->code ?? '',
                'radical_name' => $c->radical->sino_vietnamese ?? '',
                'radical_symbol' => $c->radical->radical ?? '',
            ];
        })->toArray();
    }
}
