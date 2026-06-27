<?php

namespace App\Console\Commands;

use App\Models\HskVocabulary;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImportHskFromApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hsk:import-api {--level= : Cấp độ HSK cụ thể từ 1 đến 9}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import từ vựng và câu ví dụ HSK từ API assets.losan.ai kèm phân loại chủ đề bằng Gemini AI';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $specificLevel = $this->option('level');

        if ($specificLevel) {
            $level = (int) $specificLevel;
            if ($level < 1 || $level > 9) {
                $this->error('Cấp độ HSK không hợp lý! Vui lòng chọn từ 1 đến 9.');
                return 1;
            }
            $levels = [$level];
            $this->info("Chế độ cập nhật: Đang xử lý HSK Cấp {$level}...");
        } else {
            $levels = range(1, 9);
            $this->info('Chế độ cập nhật: Đang xử lý toàn bộ HSK 1-9...');
        }

        $geminiApiKey = trim(env('GEMINI_API_KEY') ?: config('services.gemini.key'));
        if ($geminiApiKey) {
            $this->info("Đã phát hiện GEMINI_API_KEY. Hệ thống sẽ tự động phân loại chủ đề bằng Gemini AI.");
        } else {
            $this->warn("Không tìm thấy GEMINI_API_KEY trong .env. Sử dụng cơ chế mapping từ khóa dự phòng (Fallback).");
        }

        foreach ($levels as $level) {
            $this->info("==================================================");
            $this->info("Đang xử lý HSK Cấp {$level}...");

            $listUrl = "https://assets.losan.ai/api/v1/apps/hsk-flashcard/hsk{$level}";
            $explainUrl = "https://assets.losan.ai/api/v1/apps/hsk-flashcard/hsk{$level}-explain";

            $this->info("Đang tải danh sách từ vựng từ: {$listUrl} ...");
            $listResponse = Http::timeout(60)->get($listUrl);
            if ($listResponse->failed()) {
                $this->error("Lỗi khi tải danh sách từ vựng HSK {$level}!");
                continue;
            }
            $vocabList = $listResponse->json();
            if (empty($vocabList)) {
                $this->warn("Danh sách từ vựng HSK {$level} trống!");
                continue;
            }

            $this->info("Đang tải dữ liệu giải thích từ vựng từ: {$explainUrl} ...");
            $explainResponse = Http::timeout(60)->get($explainUrl);
            if ($explainResponse->failed()) {
                $this->error("Lỗi khi tải giải thích từ vựng HSK {$level}!");
                $explainData = [];
            } else {
                $explainData = $explainResponse->json();
            }

            $this->info("Đã tải " . count($vocabList) . " từ vựng. Đang kiểm tra dữ liệu hiện có trong Database...");

            // Get the list of existing words to check (avoid overwriting existing topics)
            $existingVocab = HskVocabulary::where('level', $level)->get()->keyBy('word');

            // Filter out words that NEED classification (Not in DB or topic is null/empty)
            $wordsNeedsTopic = [];
            foreach ($vocabList as $vocab) {
                $word = $vocab['hanzi'] ?? '';
                if (empty($word)) continue;

                $existing = $existingVocab->get($word);
                // If topic is null or empty, AI classification is needed
                if (!$existing || empty($existing->topic)) {
                    $wordsNeedsTopic[] = $vocab;
                }
            }

            $this->info("Có " . count($wordsNeedsTopic) . " từ vựng cần được phân loại chủ đề.");

            $allGeminiTopics = [];
            if (!empty($wordsNeedsTopic) && $geminiApiKey) {
                // Divide into chunks of 100 words to call Gemini for classification
                $vocabChunks = array_chunk($wordsNeedsTopic, 100);
                
                foreach ($vocabChunks as $chunkIndex => $vocabChunk) {
                    $this->info("\nĐang phân loại bằng Gemini AI (Batch " . ($chunkIndex + 1) . "/" . count($vocabChunks) . ")...");
                    $batchTopics = $this->classifyBatchWithGemini($vocabChunk, $geminiApiKey);
                    $allGeminiTopics = array_merge($allGeminiTopics, $batchTopics);
                    
                    sleep(1); // Avoid rate limit
                }
            }

            $this->info("\nĐang chuẩn bị dữ liệu Import/Update...");
            $insertData = [];
            $importedWords = [];
            
            $bar = $this->output->createProgressBar(count($vocabList));
            $bar->start();

            foreach ($vocabList as $vocab) {
                $word = $vocab['hanzi'] ?? '';
                $pinyin = $vocab['pinyin'] ?? '';
                $meaningVi = $vocab['meaningVi'] ?? '';
                $meaningEn = $vocab['meaningEn'] ?? '';

                if (empty($word) || in_array($word, $importedWords)) {
                    $bar->advance();
                    continue;
                }
                
                $importedWords[] = $word;

                $existing = $existingVocab->get($word);
                $topic = null;

                // Priority 1: Keep the old topic in DB if it exists
                if ($existing && !empty($existing->topic)) {
                    $topic = $existing->topic;
                } 
                // Priority 2: Get from the AI classification result
                else if (isset($allGeminiTopics[$word])) {
                    $topic = $allGeminiTopics[$word];
                }
                
                // If AI does not return a topic (or fails), we leave the topic as null.
                // Thus, the next run will automatically scan these null words for AI to retry.

                // Find and parse examples
                $exampleText = $explainData[$word] ?? '';
                $parsedExample = $this->parseExampleFromExplain($exampleText);

                $insertData[] = [
                    'word' => $word,
                    'pinyin' => $pinyin,
                    'meaning' => $meaningVi,
                    'meaning_en' => $meaningEn,
                    'level' => $level,
                    'topic' => $topic,
                    'hsk_version' => '3.0',
                    'example' => $parsedExample['example'],
                    'example_meaning' => $parsedExample['example_meaning'],
                    'created_at' => $existing ? $existing->created_at : now()->toDateTimeString(),
                    'updated_at' => now()->toDateTimeString(),
                ];

                $bar->advance();
            }
            $bar->finish();
            $this->newLine();

            // Bulk Upsert into DB in chunks of 500 words
            $this->info("Đang cập nhật dữ liệu HSK Cấp {$level} vào Database...");
            $chunks = array_chunk($insertData, 500);
            foreach ($chunks as $chunk) {
                // Use Upsert to automatically update if ['word', 'level', 'hsk_version'] is duplicated
                HskVocabulary::upsert(
                    $chunk, 
                    ['word', 'level', 'hsk_version'], 
                    ['pinyin', 'meaning', 'meaning_en', 'topic', 'example', 'example_meaning', 'updated_at']
                );
            }
            $this->info("Đã cập nhật thành công " . count($insertData) . " từ vựng HSK Cấp {$level}!");
        }

        $this->info("==================================================");
        $this->info("Hoàn tất import dữ liệu HSK từ API!");
        return 0;
    }

    /**
     * Call Gemini API to classify a batch of 100 words into topics.
     */
    protected function classifyBatchWithGemini(array $vocabChunk, string $apiKey): array
    {
        $wordsList = [];
        foreach ($vocabChunk as $vocab) {
            if (!empty($vocab['hanzi'])) {
                $wordsList[] = [
                    'w' => $vocab['hanzi'],
                    'm' => $vocab['meaningVi'] ?? '',
                    'e' => $vocab['meaningEn'] ?? ''
                ];
            }
        }

        if (empty($wordsList)) {
            return [];
        }

        // Use gemini-1.5-flash model for extreme classification speed, avoid rate limits and 404 errors
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}";

        $prompt = "Bạn là một trợ lý AI chuyên về ngôn ngữ Trung Quốc. Hãy phân loại danh sách từ vựng HSK dưới đây vào các chủ đề phù hợp nhất.\n";
        $prompt .= "Yêu cầu:\n";
        $prompt .= "- KHÔNG giới hạn số lượng chủ đề, nhưng hãy nhóm các từ vào các chủ đề lớn, khái quát (ví dụ: 'Kinh tế', 'Y tế', 'Pháp luật', 'Hành động', 'Cảm xúc', 'Thời gian', 'Gia đình', 'Du lịch', 'Mức độ', v.v.).\n";
        $prompt .= "- Tên chủ đề cần ngắn gọn (từ 1 đến 3 từ) và bằng tiếng Việt có dấu chuẩn xác.\n";
        $prompt .= "- Trả về kết quả dưới định dạng JSON duy nhất dạng key-value. Trong đó Key là chữ Hán gốc (w) và Value là tên chủ đề chính xác (hoặc null nếu không thể phân loại).\n";
        $prompt .= "Ví dụ: {\"爸爸\": \"Gia đình\", \"苹果\": \"Ăn uống\", \"跑\": \"Hành động\", \"公司\": \"Kinh tế\"}\n\n";
        $prompt .= "Danh sách từ cần phân loại:\n" . json_encode($wordsList, JSON_UNESCAPED_UNICODE);

        try {
            $response = Http::timeout(30)->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json'
                ]
            ]);

            if ($response->successful()) {
                $result = $response->json();
                $text = $result['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
                return json_decode($text, true) ?: [];
            } else {
                $this->error("\nLỗi API Gemini: " . $response->status() . " - " . $response->json('error.message', 'Lỗi không xác định'));
                if ($response->status() == 429) {
                    $this->error("=> Bạn đang bị dính Rate Limit (giới hạn gọi API) của bản Pro (thường chỉ cho phép 2 request/phút gói Free).");
                }
            }
        } catch (\Exception $e) {
            $this->error("\nLỗi kết nối Gemini: " . $e->getMessage());
            Log::error("Lỗi khi phân loại chủ đề bằng Gemini: " . $e->getMessage());
        }

        return [];
    }

    /**
     * Extract the example sentence and its Vietnamese meaning from the explanation text.
     */
    protected function parseExampleFromExplain(?string $explainText): array
    {
        $result = [
            'example' => null,
            'example_meaning' => null,
        ];

        if (empty($explainText)) {
            return $result;
        }

        if (preg_match('/Ví dụ\s*:?\s*\n/ui', $explainText, $matches, PREG_OFFSET_CAPTURE)) {
            $offset = $matches[0][1] + strlen($matches[0][0]);
            $subText = substr($explainText, $offset);

            if (preg_match_all('/^\s*\d+\.\s*([^→•\n]+?)\s*[→•]\s*(.+)$/um', $subText, $exampleMatches, PREG_SET_ORDER)) {
                $examples = [];
                $meanings = [];
                foreach ($exampleMatches as $match) {
                    $examples[] = trim($match[1]);
                    $meanings[] = trim($match[2]);
                }

                if (!empty($examples)) {
                    $result['example'] = implode("\n", $examples);
                    $result['example_meaning'] = implode("\n", $meanings);
                }
            }
        }

        return $result;
    }

}
