<?php

namespace App\Console\Commands;

use App\Models\PracticeSentence;
use App\Models\SentenceTopic;
use App\Services\ChineseWordTokenizer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImportSentencesFromFiles extends Command
{
    /**
     * Artisan command signature
     *
     * @var string
     */
    protected $signature = 'sentences:import {--fresh : Wipe existing sentence tables before importing}';

    /**
     * Command description
     *
     * @var string
     */
    protected $description = 'Import sentence topics from JSON files and copy MP3 audio files to public storage';

    /**
     * Execute the command
     */
    public function handle(): int
    {
        $this->info('🚀 Starting sentence study data import (HSK1 - HSK9)...');

        $baseSourcePath = base_path('sentences');
        if (!File::isDirectory($baseSourcePath)) {
            $this->error("❌ Source directory not found: {$baseSourcePath}");
            return Command::FAILURE;
        }

        // Wipe old data if --fresh option is provided
        if ($this->option('fresh')) {
            $this->warn('⚠️  Wiping old records in sentence_topics and practice_sentences...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            PracticeSentence::truncate();
            SentenceTopic::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->info('✅ Existing data wiped.');
        }

        $hskDirs = File::directories($baseSourcePath);
        // Natural sort HSK1 -> HSK9
        natsort($hskDirs);

        $totalTopicsCount = 0;
        $totalSentencesCount = 0;
        $totalAudioCopied = 0;

        foreach ($hskDirs as $hskDir) {
            $hskName = basename($hskDir); // HSK1, HSK2...
            if (str_contains($hskName, ':Zone.Identifier')) {
                continue;
            }

            $levelNumber = (int) filter_var($hskName, FILTER_SANITIZE_NUMBER_INT);
            if ($levelNumber <= 0) {
                continue;
            }

            $topicDirs = File::directories($hskDir);
            natsort($topicDirs);

            $this->line("<fg=cyan>📂 Scanning level {$hskName} (" . count($topicDirs) . " topics)...</>");

            foreach ($topicDirs as $topicDir) {
                if (str_contains(basename($topicDir), ':Zone.Identifier')) {
                    continue;
                }

                $files = File::files($topicDir);
                $jsonFile = null;
                foreach ($files as $file) {
                    if ($file->getExtension() === 'json' && !str_contains($file->getFilename(), ':Zone.Identifier')) {
                        $jsonFile = $file;
                        break;
                    }
                }

                if (!$jsonFile) {
                    continue;
                }

                $rawContent = File::get($jsonFile->getRealPath());
                $data = json_decode($rawContent, true);

                if (!$data || !is_array($data)) {
                    $this->warn("⚠️ Skipping corrupted JSON file: " . $jsonFile->getFilename());
                    continue;
                }

                // Extract topic metadata
                $slug = trim($data['id'] ?? Str::slug($data['title'] ?? basename($topicDir)));
                $title = trim($data['title'] ?? basename($topicDir));
                $titleVi = isset($data['titleVi']) ? trim($data['titleVi']) : null;
                $hanzi = isset($data['hanzi']) ? trim($data['hanzi']) : null;
                $pinyin = isset($data['pinyin']) ? trim($data['pinyin']) : null;
                $level = (int) ($data['level'] ?? $levelNumber);

                // Source audio directory
                $sourceAudioDir = $topicDir . DIRECTORY_SEPARATOR . 'audio';

                // Target audio directory in public storage: storage/app/public/sentences/hsk{level}/{slug}/audio
                $levelSlug = 'hsk' . $level;
                $storageRelativeDir = "sentences/{$levelSlug}/{$slug}/audio";
                $targetAudioDir = storage_path("app/public/{$storageRelativeDir}");

                // Create or update topic
                $topic = SentenceTopic::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'level' => $level,
                        'title' => $title,
                        'title_vi' => $titleVi,
                        'hanzi' => $hanzi,
                        'pinyin' => $pinyin,
                    ]
                );

                $sentencesData = $data['sentences'] ?? [];
                $importedSentencesInTopic = 0;

                // Remove existing sentences to prevent duplicates
                $topic->sentences()->delete();

                foreach ($sentencesData as $index => $s) {
                    // Normalize Chinese characters
                    $sentenceHanzi = trim($s['hanzi'] ?? $s['hangzi'] ?? '');
                    if ($sentenceHanzi === '') {
                        continue;
                    }

                    $sentencePinyin = isset($s['pinyin']) ? trim($s['pinyin']) : null;
                    $sentenceMeaning = isset($s['meaning']) ? trim($s['meaning']) : null;
                    $sentenceMeaningVi = isset($s['meaningVi']) ? trim($s['meaningVi']) : null;
                    $duration = (int) ($s['duration'] ?? 0);
                    $words = $s['words'] ?? null;

                    // Audio file handling
                    $audioPathForDb = null;
                    $audioFilename = null;

                    if (!empty($s['audioUrl'])) {
                        $audioFilename = basename($s['audioUrl']);
                    } else {
                        $audioFilename = "{$index}.mp3";
                    }

                    $sourceMp3File = $sourceAudioDir . DIRECTORY_SEPARATOR . $audioFilename;

                    // Copy MP3 file to storage if it exists
                    if (File::exists($sourceMp3File)) {
                        File::ensureDirectoryExists($targetAudioDir);
                        $targetMp3File = $targetAudioDir . DIRECTORY_SEPARATOR . $audioFilename;

                        if (!File::exists($targetMp3File) || File::size($targetMp3File) !== File::size($sourceMp3File)) {
                            File::copy($sourceMp3File, $targetMp3File);
                            $totalAudioCopied++;
                        }

                        $audioPathForDb = "{$storageRelativeDir}/{$audioFilename}";
                    }

                    // Tokenize into HSK compound word tokens
                    $tokens = ChineseWordTokenizer::tokenize($sentenceHanzi, $sentencePinyin);

                    // Save practice sentence into database
                    PracticeSentence::create([
                        'topic_id' => $topic->id,
                        'order_index' => $index,
                        'hanzi' => $sentenceHanzi,
                        'pinyin' => $sentencePinyin,
                        'meaning' => $sentenceMeaning,
                        'meaning_vi' => $sentenceMeaningVi,
                        'audio_path' => $audioPathForDb,
                        'duration' => $duration,
                        'words' => $words,
                        'tokens' => $tokens,
                    ]);

                    $importedSentencesInTopic++;
                    $totalSentencesCount++;
                }

                // Update total sentence count for topic
                $topic->update(['total_sentences' => $importedSentencesInTopic]);
                $totalTopicsCount++;
            }
        }

        $this->newLine();
        $this->info("🎉 HOÀN TẤT NHẬP DỮ LIỆU THÀNH CÔNG!");
        $this->table(
            ['Hạng mục', 'Số lượng'],
            [
                ['Tổng số Chủ đề (Topics)', $totalTopicsCount],
                ['Tổng số Câu luyện tập (Sentences)', $totalSentencesCount],
                ['Số file Audio MP3 đã đồng bộ vào Storage', $totalAudioCopied],
            ]
        );

        return Command::SUCCESS;
    }
}
