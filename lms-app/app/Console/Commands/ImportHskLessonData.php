<?php

namespace App\Console\Commands;

use App\Models\HskLevel;
use App\Models\HskLesson;
use App\Models\HskLessonVocab;
use App\Models\HskLessonGrammar;
use App\Models\HskLessonDialogueSection;
use App\Models\HskLessonDialogue;
use App\Models\HskLessonPractice;
use App\Models\HskLessonPracticeSection;
use App\Models\HskLessonPracticeQuestion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ImportHskLessonData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hsk:import-lessons {path? : Path to the tiengtrungbotui_data directory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import levels, lessons, vocabulary and grammar of HSK Standard Course from crawled JSON directory.';

    /**
     * Design Tokens mapping for the 6 HSK levels
     */
    protected $levelDesignMap = [
        'hsk1' => [
            'subtitle' => 'Khởi đầu Hán ngữ',
            'color' => 'emerald',
            'spine_color' => 'bg-[#eab308]',
            'cover_bg' => 'bg-[#fffdf5] border-[#fef08a]',
            'number_color' => 'text-[#eab308]'
        ],
        'hsk2' => [
            'subtitle' => 'Sơ cấp Hán ngữ',
            'color' => 'cyan',
            'spine_color' => 'bg-[#0f766e]',
            'cover_bg' => 'bg-[#eefcfb] border-[#d6f5f3]',
            'number_color' => 'text-[#0f766e]'
        ],
        'hsk3' => [
            'subtitle' => 'Trung cấp Hán ngữ',
            'color' => 'blue',
            'spine_color' => 'bg-[#dc2626]',
            'cover_bg' => 'bg-[#fff5f5] border-[#fecaca]',
            'number_color' => 'text-[#dc2626]'
        ],
        'hsk4' => [
            'subtitle' => 'Trung - Cao cấp',
            'color' => 'purple',
            'spine_color' => 'bg-[#6d28d9]',
            'cover_bg' => 'bg-[#faf5ff] border-[#f3e8ff]',
            'number_color' => 'text-[#6d28d9]'
        ],
        'hsk5' => [
            'subtitle' => 'Cao cấp Hán ngữ',
            'color' => 'rose',
            'spine_color' => 'bg-[#be185d]',
            'cover_bg' => 'bg-[#fff5f7] border-[#ffe4e6]',
            'number_color' => 'text-[#be185d]'
        ],
        'hsk6' => [
            'subtitle' => 'Thượng thừa Hán ngữ',
            'color' => 'amber',
            'spine_color' => 'bg-[#1d4ed8]',
            'cover_bg' => 'bg-[#eff6ff] border-[#dbeafe]',
            'number_color' => 'text-[#1d4ed8]'
        ]
    ];

    /**
     * Map of lesson titles populated from lessons.json
     */
    protected $lessonTitlesMap = [];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $crawlPath = $this->argument('path') ?: base_path('tiengtrungbotui_data');

        if (!File::exists($crawlPath)) {
            $this->error("Thư mục dữ liệu không tồn tại: {$crawlPath}");
            return 1;
        }

        $this->info("=== Bắt đầu import dữ liệu Giáo trình HSK từ: {$crawlPath} ===");

        $lessonsJsonPath = "{$crawlPath}/lessons.json";
        if (File::exists($lessonsJsonPath)) {
            $lessonsData = json_decode(File::get($lessonsJsonPath), true);
            if (isset($lessonsData['lessons'])) {
                foreach ($lessonsData['lessons'] as $l) {
                    if ($l['id'] === 'lesson9999') continue;
                    $lNum = (int) str_replace('lesson', '', $l['id']);
                    if (!isset($this->lessonTitlesMap[$l['level_id']][$lNum])) {
                        $this->lessonTitlesMap[$l['level_id']][$lNum] = [
                            'title' => $l['name'],
                            'pinyin' => '',
                            'translation' => $l['name_vi']
                        ];
                    }
                }
                $this->info("Đã nạp thành công dữ liệu tên bài học từ lessons.json");
            }
        }

        // levels are statically defined similarly to the Python crawler
        $levels = [
            ['id' => 'hsk1', 'name' => 'HSK 1', 'lesson_count' => 15, 'vocab_count' => 150],
            ['id' => 'hsk2', 'name' => 'HSK 2', 'lesson_count' => 15, 'vocab_count' => 300],
            ['id' => 'hsk3', 'name' => 'HSK 3', 'lesson_count' => 20, 'vocab_count' => 600],
            ['id' => 'hsk4', 'name' => 'HSK 4', 'lesson_count' => 20, 'vocab_count' => 1200],
            ['id' => 'hsk5', 'name' => 'HSK 5', 'lesson_count' => 36, 'vocab_count' => 2500],
            ['id' => 'hsk6', 'name' => 'HSK 6', 'lesson_count' => 40, 'vocab_count' => 5000],
        ];

        DB::beginTransaction();
        try {
            foreach ($levels as $levelItem) {
                $levelId = $levelItem['id']; // hsk1, hsk2...
                $levelName = $levelItem['name'];
                $lessonCount = $levelItem['lesson_count'];
                $vocabCount = $levelItem['vocab_count'];

                $this->info("--------------------------------------------------");
                $this->info("Đang xử lý Cấp độ HSK: {$levelName} (Số bài học: {$lessonCount})");

                // 1. Create/Update HskLevel
                $design = $this->levelDesignMap[$levelId] ?? [
                    'subtitle' => 'Giáo trình chuẩn HSK',
                    'color' => 'blue',
                    'spine_color' => 'bg-blue-600',
                    'cover_bg' => 'bg-blue-50 border-blue-200',
                    'number_color' => 'text-blue-600'
                ];

                $level = HskLevel::updateOrCreate(
                    ['level_code' => $levelId],
                    [
                        'title' => $levelName,
                        'slug' => Str::slug($levelName),
                        'subtitle' => $design['subtitle'],
                        'color' => $design['color'],
                        'spine_color' => $design['spine_color'],
                        'cover_bg' => $design['cover_bg'],
                        'number_color' => $design['number_color'],
                        'lessons_count' => $lessonCount,
                        'vocab_count' => $vocabCount,
                        'duration' => $lessonCount * 2 . ' giờ'
                    ]
                );

                // Scan each lesson in the level
                for ($lNum = 1; $lNum <= $lessonCount; $lNum++) {
                    $lNumStr = sprintf('%02d', $lNum);

                    // 2. Get lesson title info (Prioritize static mapping, fallback to default)
                    $titleInfo = $this->lessonTitlesMap[$levelId][$lNum] ?? [
                        'title' => "Bài {$lNum}",
                        'pinyin' => "Bàizhī {$lNum}",
                        'translation' => "Bài học số {$lNum}"
                    ];

                    $lesson = HskLesson::updateOrCreate(
                        [
                            'hsk_level_id' => $level->id,
                            'lesson_number' => $lNum
                        ],
                        [
                            'title' => $titleInfo['title'],
                            'slug' => Str::slug('bai ' . $lNumStr . ' ' . $titleInfo['title'] . '-' . $titleInfo['translation']),
                            'pinyin' => $titleInfo['pinyin'],
                            'translation' => $titleInfo['translation'],
                            'code' => 'H' . substr($levelId, 3) . 'L' . $lNumStr
                        ]
                    );

                    // 3. Read and Import Vocabulary (Vocab JSON)
                    $vocabFile = "{$crawlPath}/json/{$levelId}/lesson_{$lNumStr}_vocab.json";
                    if (File::exists($vocabFile)) {
                        $vocabData = json_decode(File::get($vocabFile), true);

                        HskLessonVocab::where('hsk_lesson_id', $lesson->id)->delete();

                        foreach ($vocabData as $vItem) {
                            $word = $vItem['han'] ?? $vItem['word'] ?? $vItem['hanzi'] ?? null;
                            if (empty($word)) continue;

                            // Relative audio path matching the crawled directory structure
                            $audioRelativePath = "{$levelId}/lesson_{$lNumStr}/vocab/{$word}.mp3";

                            HskLessonVocab::create([
                                'hsk_lesson_id' => $lesson->id,
                                'word' => $word,
                                'pinyin' => $vItem['pinyin'] ?? '',
                                'type' => $vItem['pos'] ?? $vItem['type'] ?? 'Từ vựng',
                                'meaning' => $vItem['vi'] ?? $vItem['meaning'] ?? '',
                                'audio_path' => $audioRelativePath
                            ]);
                        }
                    }

                    // 4. Read and Import Grammar (Grammar JSON)
                    $grammarFile = "{$crawlPath}/json/{$levelId}/lesson_{$lNumStr}_grammar.json";
                    if (File::exists($grammarFile)) {
                        $grammarData = json_decode(File::get($grammarFile), true);

                        HskLessonGrammar::where('hsk_lesson_id', $lesson->id)->delete();

                        foreach ($grammarData as $gItem) {
                            $title = $gItem['pattern'] ?? $gItem['title'] ?? null;
                            if (empty($title)) continue;

                            // Parse flat example from actual JSON
                            $examples = [];
                            if (!empty($gItem['example'])) {
                                $examples[] = [
                                    'character' => $gItem['example'],
                                    'pinyin' => $gItem['pinyin'] ?? '',
                                    'translation' => $gItem['meaning'] ?? ''
                                ];
                            }

                            HskLessonGrammar::create([
                                'hsk_lesson_id' => $lesson->id,
                                'title' => $title,
                                'formula' => $gItem['structure'] ?? $gItem['formula'] ?? '',
                                'explanation' => $gItem['explanation'] ?? '',
                                'examples' => $examples
                            ]);
                        }
                    }

                    // 5. Initialize dialogue section loaded directly from API tiengtrungbotui.com
                    $textApiUrl = "https://tiengtrungbotui.com/data/{$levelId}/texts/lesson{$lNum}.json";

                    HskLessonDialogueSection::where('hsk_lesson_id', $lesson->id)->delete();

                    try {
                        $this->info("--> Đang tải dữ liệu bài khóa từ API: {$textApiUrl}");
                        $response = Http::timeout(10)->get($textApiUrl);

                        if ($response->successful()) {
                            $dialogueSectionsData = $response->json();
                            if (is_array($dialogueSectionsData)) {
                                foreach ($dialogueSectionsData as $index => $sectData) {
                                    $partNum = $index + 1;

                                    // Create dialogue title
                                    $textHan = !empty($sectData['text_han']) ? trim($sectData['text_han']) : '';
                                    $textVi = !empty($sectData['text_vi']) ? trim($sectData['text_vi']) : "Bài khóa {$partNum}";
                                    $titleHan = !empty($sectData['title_han']) ? trim($sectData['title_han']) : '';
                                    $titleVi = !empty($sectData['title_vi']) ? trim($sectData['title_vi']) : '';

                                    $fullHan = $textHan;
                                    if ($titleHan) $fullHan .= '：' . $titleHan;

                                    $fullVi = $textVi;
                                    if ($titleVi) $fullVi .= ': ' . $titleVi;

                                    $title = $fullHan;
                                    if ($fullVi) $title .= " ($fullVi)";

                                    // Get corresponding audio filename based on structure
                                    $audioFile = sprintf('%02d-%d.mp3', $lNum, $partNum);
                                    $audioRelativePath = "{$levelId}/lesson_{$lNumStr}/gt/{$audioFile}";

                                    $section = HskLessonDialogueSection::create([
                                        'hsk_lesson_id' => $lesson->id,
                                        'title' => $title,
                                        'audio_path' => $audioRelativePath
                                    ]);

                                    // Import detailed dialogue lines
                                    if (isset($sectData['content']) && is_array($sectData['content'])) {
                                        foreach ($sectData['content'] as $dialogLine) {
                                            HskLessonDialogue::create([
                                                'dialogue_section_id' => $section->id,
                                                'role' => $dialogLine['speaker'] ?? $dialogLine['speaker_vi'] ?? '',
                                                'character' => $dialogLine['text_han'] ?? '',
                                                'pinyin' => $dialogLine['pinyin'] ?? '',
                                                'translation' => $dialogLine['meaning'] ?? '',
                                                'audio_path' => isset($dialogLine['audio']) ? 'https://media.tiengtrungbotui.com/texts/audio/' . $dialogLine['audio'] : null
                                            ]);
                                        }
                                    }
                                }
                            }
                        } else {
                            $this->warn("--> Không tải được text bài khóa từ API (Mã lỗi: {$response->status()}). Sử dụng fallback audio.");
                            $this->fallbackDialogueImport($lesson, $levelId, $lNumStr);
                        }
                    } catch (\Exception $ex) {
                        $this->warn("--> Lỗi kết nối tải bài khóa: {$ex->getMessage()}. Sử dụng fallback audio.");
                        $this->fallbackDialogueImport($lesson, $levelId, $lNumStr);
                    }

                    // 6. Import Practice
                    $practiceFile = "{$crawlPath}/json/{$levelId}/lesson_{$lNumStr}_practice.json";
                    if (File::exists($practiceFile)) {
                        $practiceData = json_decode(File::get($practiceFile), true);

                        // Clear old practices for this lesson
                        HskLessonPractice::where('hsk_lesson_id', $lesson->id)->delete();

                        $types = ['listening', 'reading', 'writing'];
                        foreach ($types as $pType) {
                            if (isset($practiceData[$pType])) {
                                $pData = $practiceData[$pType];
                                $audioPath = null;
                                if (isset($pData['audio'])) {
                                    $audioPath = "audio/" . $pData['audio'] . ".mp3";
                                }

                                $practice = HskLessonPractice::create([
                                    'hsk_lesson_id' => $lesson->id,
                                    'type' => $pType,
                                    'audio_path' => $audioPath
                                ]);

                                $sections = $pData['sections'] ?? [];
                                foreach ($sections as $secId => $secData) {
                                    $sectionAudioPath = null;
                                    if (isset($secData['audio'])) {
                                        $subFolderPath = "audio/{$levelId}/lesson_{$lNumStr}/bt/" . $secData['audio'] . ".mp3";
                                        $subFolderFullPath = storage_path("app/public/hsk_media/{$subFolderPath}");
                                        if (File::exists($subFolderFullPath)) {
                                            $sectionAudioPath = $subFolderPath;
                                        } else {
                                            $sectionAudioPath = "audio/" . $secData['audio'] . ".mp3";
                                        }
                                    }

                                    $section = HskLessonPracticeSection::create([
                                        'practice_id' => $practice->id,
                                        'section_han' => $secData['section_han'] ?? null,
                                        'section_vi' => $secData['section_vi'] ?? null,
                                        'audio_path' => $sectionAudioPath,
                                        'image_path' => !empty($secData['imageFileName']) ? "images/" . $secData['imageFileName'] : null
                                    ]);

                                    $questions = $secData['questions'] ?? [];
                                    foreach ($questions as $q) {
                                        HskLessonPracticeQuestion::create([
                                            'section_id' => $section->id,
                                            'ques_id' => $q['ques_id'] ?? 0,
                                            'ques_type' => $q['ques_type'] ?? 'true_false',
                                            'question' => $q['question'] ?? null,
                                            'context' => $q['context'] ?? null,
                                            'question_pinyin' => $q['question_pinyin'] ?? null,
                                            'options' => $q['options'] ?? $q['hints'] ?? [],
                                            'sub_questions' => $q['sub_questions'] ?? null,
                                            'correct_answer' => isset($q['correct']) ? (is_array($q['correct']) ? json_encode($q['correct']) : $q['correct']) : null,
                                            'question_segments' => $q['question_segments'] ?? null,
                                            'image_path' => !empty($q['imageFileName']) ? "images/" . $q['imageFileName'] : null
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }

                // Update the actual number of vocabulary words after importing all lessons of the level
                $actualVocabCount = HskLessonVocab::whereIn(
                    'hsk_lesson_id',
                    HskLesson::where('hsk_level_id', $level->id)->pluck('id')
                )->count();

                $level->update(['vocab_count' => $actualVocabCount]);
                $this->info("=> Cập nhật tổng số từ vựng thực tế cho {$levelName}: {$actualVocabCount} từ.");
            }

            DB::commit();
            $this->info("==================================================");
            $this->info("✅ Đã IMPORT THÀNH CÔNG toàn bộ dữ liệu bài học HSK!");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ Gặp lỗi trong quá trình import: " . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Fallback method when API fails: Scan local gt audio directory and create empty dialogue sections
     */
    protected function fallbackDialogueImport($lesson, $levelId, $lNumStr)
    {
        $gtAudioDir = base_path("tiengtrungbotui_data/audio/{$levelId}/lesson_{$lNumStr}/gt");
        if (File::isDirectory($gtAudioDir)) {
            $files = File::files($gtAudioDir);
            sort($files);

            foreach ($files as $file) {
                $filename = $file->getFilename();
                if (preg_match('/-(\d+)\.mp3$/i', $filename, $matches)) {
                    $partNum = (int)$matches[1];
                    $maxParts = ($levelId === 'hsk1' || $levelId === 'hsk2') ? 3 : (($levelId === 'hsk3') ? 4 : 5);

                    if ($partNum > $maxParts) {
                        continue;
                    }

                    $section = HskLessonDialogueSection::create([
                        'hsk_lesson_id' => $lesson->id,
                        'title' => "Bài khóa {$partNum}",
                        'audio_path' => "{$levelId}/lesson_{$lNumStr}/gt/{$filename}"
                    ]);

                    HskLessonDialogue::create([
                        'dialogue_section_id' => $section->id,
                        'role' => 'A',
                        'character' => '你好！ (Nghe âm thanh đính kèm để luyện nói)',
                        'pinyin' => 'Nǐ hǎo!',
                        'translation' => 'Xin chào!'
                    ]);
                }
            }
        }
    }
}
