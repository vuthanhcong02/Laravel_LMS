<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\PinyinTone;
use App\Models\PinyinExample;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PinyinImportExamplesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pinyin:import-examples';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động cào toàn bộ từ vựng HSK 1-6 từ API về và gắn vào 1572 thanh điệu Pinyin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Bắt đầu đồng bộ toàn bộ từ vựng HSK 1-6 vào bảng Pinyin Examples...');

        // Clear existing examples
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PinyinExample::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Key tones by binary display
        $allTones = PinyinTone::all()->keyBy('display');

        $totalInserted = 0;
        $matchedToneIds = [];

        for ($level = 1; $level <= 6; $level++) {
            $url = "https://assets.losan.ai/api/v1/apps/hsk-flashcard/hsk{$level}";
            $this->info("Đang tải dữ liệu HSK{$level} từ: {$url} ...");

            try {
                $response = Http::timeout(15)->get($url);
                if (!$response->successful()) {
                    $this->warn("Không thể tải HSK{$level}, bỏ qua...");
                    continue;
                }

                $items = $response->json();
                if (!is_array($items)) continue;

                foreach ($items as $item) {
                    $rawHanzi = $item['hanzi'] ?? '';
                    $rawPinyin = $item['pinyin'] ?? '';
                    $meaning = $item['meaningVi'] ?? ($item['meaningEn'] ?? '');
                    $levelStr = "HSK{$level}";

                    if (empty($rawHanzi) || empty($rawPinyin)) continue;

                    // Clean Hanzi (e.g., "爸爸 / 爸" -> "爸")
                    $hanziParts = preg_split('/[\/\|]/', $rawHanzi);
                    $hanzi = trim(end($hanziParts));
                    $hanzi = preg_replace('/[（\(].*?[）\)]/u', '', $hanzi);
                    $hanzi = trim($hanzi);

                    // Clean Pinyin (e.g., "dìdi | dì" -> "dì")
                    $pinyinParts = preg_split('/[\/\|]/', $rawPinyin);
                    $pinyinStr = trim($pinyinParts[0]);
                    $pinyinStr = preg_replace('/[（\(].*?[）\)]/u', '', $pinyinStr);
                    $pinyinStr = trim($pinyinStr);

                    // Match first syllable pinyin to tone model
                    $syllables = explode(' ', str_replace(['’', "'"], '', $pinyinStr));
                    $firstSyl = trim($syllables[0] ?? '');
                    if (empty($firstSyl)) continue;

                    $toneModel = $allTones->get($firstSyl);
                    if ($toneModel) {
                        // Check if example already exists for this tone & hanzi
                        $exists = PinyinExample::where('pinyin_tone_id', $toneModel->id)
                            ->where('hanzi', $hanzi)
                            ->exists();

                        if (!$exists) {
                            PinyinExample::create([
                                'pinyin_tone_id' => $toneModel->id,
                                'hanzi' => $hanzi,
                                'pinyin' => $pinyinStr,
                                'meaning' => $meaning,
                                'level' => $levelStr,
                            ]);
                            $totalInserted++;
                            $matchedToneIds[$toneModel->id] = true;
                        }
                    }
                }
            } catch (\Exception $e) {
                $this->error("Lỗi khi xử lý HSK{$level}: " . $e->getMessage());
            }
        }

        Cache::forget('pinyin_data');
        $coveredCount = count($matchedToneIds);
        $this->info("Đồng bộ hoàn tất! Đã thêm {$totalInserted} từ vựng minh họa phủ kín {$coveredCount} / 1572 thanh điệu Pinyin!");
    }
}
