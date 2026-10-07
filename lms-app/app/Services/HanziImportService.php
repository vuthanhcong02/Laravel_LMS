<?php

namespace App\Services;

use App\Models\HanziCharacter;
use App\Models\HanziComponent;
use App\Models\HanziCompoundWord;
use App\Models\HanziExample;
use App\Models\HanziRadical;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class HanziImportService
{
    protected HanziStrokeStorageService $strokeStorage;

    public function __construct(HanziStrokeStorageService $strokeStorage)
    {
        $this->strokeStorage = $strokeStorage;
    }

    /**
     * Nhập toàn bộ 214 bộ thủ Khang Hy và Danh mục Chữ Hán từ các tệp dữ liệu JSON chuẩn
     */
    public function importAll(): array
    {
        $radicalsPath = database_path('data/hanzi/radicals_214.json');
        $charactersPath = database_path('data/hanzi/characters_dictionary.json');

        if (!File::exists($radicalsPath)) {
            throw new \RuntimeException("Không tìm thấy tệp dữ liệu bộ thủ: {$radicalsPath}");
        }

        $radicalsData = json_decode(File::get($radicalsPath), true) ?: [];
        $charactersData = File::exists($charactersPath) ? (json_decode(File::get($charactersPath), true) ?: []) : [];

        // Xóa sạch dữ liệu cũ trước khi nạp dữ liệu chuẩn mới
        HanziExample::query()->delete();
        HanziCompoundWord::query()->delete();
        HanziComponent::query()->delete();
        HanziCharacter::query()->delete();

        DB::transaction(function () use ($radicalsData, $charactersData, &$importedRadicals, &$importedCharacters) {
            $validCodes = [];
            $radicalModels = [];

            // 1. Đồng bộ 214 Bộ thủ Khang Hy
            $order = 1;
            foreach ($radicalsData as $item) {
                $code = $item['id'];
                $validCodes[] = $code;

                $radical = HanziRadical::updateOrCreate(
                    ['code' => $code],
                    [
                        'radical' => $item['radical'],
                        'original' => $item['original'] ?? null,
                        'sino_vietnamese' => $item['sino_vietnamese'],
                        'pinyin' => $item['pinyin'],
                        'stroke_count' => (int) $item['stroke_count'],
                        'char_count' => (int) ($item['char_count'] ?? count($item['characters'] ?? [])),
                        'tone' => $item['tone'] ?? null,
                        'meaning' => $item['meaning'],
                        'description' => $item['description'] ?? null,
                        'word_type' => $item['word_type'] ?? 'Danh từ 名词',
                        'word_type_desc' => $item['word_type_desc'] ?? null,
                        'sort_order' => $order++,
                        'is_active' => true,
                    ]
                );

                $radicalModels[$code] = $radical;
                $importedRadicals++;

                // Lưu vector stroke cho bộ thủ
                $this->strokeStorage->saveRadicalStrokeData(
                    $item['radical'],
                    $code,
                    (int) $item['stroke_count'],
                    [
                        'strokes' => ['M 50 10 L 50 90'],
                        'medians' => [[[50, 10], [50, 90]]],
                    ]
                );
            }

            // Xóa các bộ thủ cũ không hợp lệ
            HanziRadical::whereNotIn('code', $validCodes)->delete();

            // Tạo bảng tra cứu chi tiết chữ Hán (components, compounds, examples)
            $dictLookup = [];
            foreach ($charactersData as $c) {
                if (!empty($c['character'])) {
                    $dictLookup[$c['character']] = $c;
                }
            }

            // 2. Đồng bộ Chữ Hán, Chiết tự, Từ ghép theo đúng từng bộ thủ chuẩn XieHanzi
            foreach ($radicalsData as $radItem) {
                $radCode = $radItem['id'];
                $radical = $radicalModels[$radCode] ?? null;

                if (!$radical || empty($radItem['characters'])) {
                    continue;
                }

                foreach ($radItem['characters'] as $charItem) {
                    $char = $charItem['char'];
                    $extra = $dictLookup[$char] ?? [];

                    $hsk = (int) ($charItem['hsk'] ?? ($extra['hsk_level'] ?? 1));
                    $strokeCnt = (int) ($charItem['stroke_count'] ?? ($extra['stroke_count'] ?? 1));
                    $pinyin = $charItem['pinyin'] ?: ($extra['pinyin'] ?? '');
                    $sino = $charItem['sino_vietnamese'] ?: ($extra['sino_vietnamese'] ?? '');
                    $meaning = $charItem['meaning'] ?: ($extra['meaning'] ?? '');
                    $tone = $charItem['tone'] ?? ($extra['tone'] ?? null);

                    // Lưu đường dẫn file vector nét vẽ
                    $strokeFilePath = $this->strokeStorage->saveStrokeData(
                        $char,
                        [
                            'stroke_count' => $strokeCnt,
                            'strokes' => array_fill(0, $strokeCnt, 'M 10 10 L 90 90'),
                            'medians' => array_fill(0, $strokeCnt, [[10, 10], [90, 90]]),
                            'stroke_names' => array_map(fn($i) => "Nét {$i}", range(1, $strokeCnt)),
                        ],
                        $hsk,
                        $radCode
                    );

                    $charModel = HanziCharacter::updateOrCreate(
                        [
                            'radical_id' => $radical->id,
                            'character' => $char,
                        ],
                        [
                            'unicode_hex' => $this->strokeStorage->getUnicodeHex($char),
                            'pinyin' => $pinyin,
                            'sino_vietnamese' => $sino,
                            'meaning' => $meaning,
                            'hsk_level' => $hsk,
                            'stroke_count' => $strokeCnt,
                            'tone' => $tone,
                            'formation_type' => $extra['formation_type'] ?? ($char === $radical->radical ? 'Tượng hình (象形)' : 'Hội ý (会意)'),
                            'radical_relation' => $extra['radical_relation'] ?? "Chữ {$char} mang bộ {$radical->sino_vietnamese} ({$radical->radical}).",
                            'formation_desc' => $extra['formation_desc'] ?? "Cấu tạo gồm bộ {$radical->sino_vietnamese} ({$radical->radical}): {$meaning}.",
                            'mnemonic_poem' => $extra['mnemonic_poem'] ?? "Nét chữ {$char} sáng tựa trăng sao\nBộ {$radical->sino_vietnamese} gìn giữ thanh cao trọn đời.",
                            'traditional_quote' => $extra['traditional_quote'] ?? "Chữ {$char} tự nguyên gắn liền với bộ {$radical->sino_vietnamese}.",
                            'naming_significance' => $extra['naming_significance'] ?? "Hàm ý tươi sáng, phát triển.",
                            'popularity_rank' => $hsk * 20,
                            'stroke_file_path' => $strokeFilePath,
                            'is_published' => true,
                        ]
                    );

                    $importedCharacters++;

                    // Lưu các thành phần cấu tạo (Components)
                    if (!empty($extra['components'])) {
                        foreach ($extra['components'] as $idx => $comp) {
                            HanziComponent::updateOrCreate(
                                [
                                    'character_id' => $charModel->id,
                                    'component_char' => $comp['character'],
                                ],
                                [
                                    'component_name' => $comp['name'] ?? "Thành phần {$comp['character']}",
                                    'role' => $comp['role'] ?? 'Biểu ý',
                                    'meaning' => $comp['meaning'] ?? $meaning,
                                    'explanation' => $comp['explanation'] ?? '',
                                    'sort_order' => $idx + 1,
                                ]
                            );
                        }
                    }

                    // Lưu từ ghép thông dụng (Compounds)
                    if (!empty($extra['compounds'])) {
                        foreach ($extra['compounds'] as $compound) {
                            HanziCompoundWord::updateOrCreate(
                                [
                                    'character_id' => $charModel->id,
                                    'word' => $compound['word'],
                                ],
                                [
                                    'pinyin' => $compound['pinyin'] ?? '',
                                    'sino_vietnamese' => $compound['sino_vietnamese'] ?? '',
                                    'meaning' => $compound['meaning'] ?? '',
                                    'hsk_level' => $compound['hsk_level'] ?? $hsk,
                                ]
                            );
                        }
                    }

                    // Lưu câu ví dụ (Examples)
                    if (!empty($extra['examples'])) {
                        foreach ($extra['examples'] as $example) {
                            HanziExample::updateOrCreate(
                                [
                                    'character_id' => $charModel->id,
                                    'sentence_zh' => $example['sentence_zh'],
                                ],
                                [
                                    'sentence_pinyin' => $example['sentence_pinyin'] ?? '',
                                    'sentence_vi' => $example['sentence_vi'] ?? '',
                                ]
                            );
                        }
                    }
                }
            }
        });

        // Xóa cache để cập nhật dữ liệu mới
        Cache::flush();

        return [
            'radicals' => $importedRadicals,
            'characters' => $importedCharacters,
        ];
    }
}
