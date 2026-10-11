<?php

namespace App\Console\Commands;

use App\Models\HskLessonPractice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class StandardizeHskAudioPaths extends Command
{
    /**
     * Console command signature
     *
     * @var string
     */
    protected $signature = 'hsk:standardize-audio';

    /**
     * Console command description
     *
     * @var string
     */
    protected $description = 'Standardize HSK audio paths into hierarchical structure and remove duplicate root files';

    /**
     * Execute the console command
     */
    public function handle(): int
    {
        $this->info('=== STARTING HSK AUDIO PATH STANDARDIZATION ===');

        $baseAudioPath = storage_path('app/public/hsk_media/audio');

        // 1. Move 20 HSK 3 audio files from root audio/{01..20}.mp3 into hsk3/lesson_{01..20}/bt/
        $this->line("\n1. Reorganizing 20 HSK 3 audio files into practice (bt) folders...");
        $movedCount = 0;
        for ($i = 1; $i <= 20; $i++) {
            $numStr = sprintf('%02d', $i);
            $rootFile = "{$baseAudioPath}/{$numStr}.mp3";
            $targetDir = "{$baseAudioPath}/hsk3/lesson_{$numStr}/bt";
            $targetFile = "{$targetDir}/{$numStr}.mp3";

            if (File::exists($rootFile)) {
                if (!File::isDirectory($targetDir)) {
                    File::makeDirectory($targetDir, 0755, true);
                }
                File::move($rootFile, $targetFile);
                $this->line("  ✓ Moved {$numStr}.mp3 -> hsk3/lesson_{$numStr}/bt/{$numStr}.mp3");
                $movedCount++;
            }
        }
        $this->info("✓ Reorganized {$movedCount} HSK 3 audio files into hierarchical structure.");

        // 2. Remove duplicate HSK 2 files at root audio (01-1.mp3 to 15-1.mp3)
        $this->line("\n2. Cleaning up duplicate files in root audio/ directory...");
        $deletedCount = 0;
        for ($i = 1; $i <= 15; $i++) {
            $numStr = sprintf('%02d', $i);
            $duplicateFile = "{$baseAudioPath}/{$numStr}-1.mp3";
            $realFile = "{$baseAudioPath}/hsk2/lesson_{$numStr}/bt/{$numStr}-1.mp3";

            // Only delete if the canonical file in hsk2/lesson_xx/bt exists
            if (File::exists($duplicateFile) && File::exists($realFile)) {
                File::delete($duplicateFile);
                $this->line("  ✓ Deleted duplicate file: {$numStr}-1.mp3 (Original exists in hsk2/lesson_{$numStr}/bt/)");
                $deletedCount++;
            }
        }
        $this->info("✓ Deleted {$deletedCount} duplicate files in root directory.");

        // 3. Standardize database audio_path column for hsk_lesson_practices table
        $this->line("\n3. Standardizing audio_path column in database...");
        $practices = HskLessonPractice::whereNotNull('audio_path')->with('lesson.level')->get();
        $updatedDbCount = 0;

        foreach ($practices as $p) {
            $lesson = $p->lesson;
            if (!$lesson || !$lesson->level) {
                continue;
            }

            $levelCode = str_replace('-', '', $lesson->level->slug ?? 'hsk' . $lesson->level->id);
            $lNumStr = sprintf('%02d', $lesson->lesson_number);
            $rawFilename = basename($p->audio_path);

            // Construct hierarchical path in practice (bt) with audio/ prefix
            $candidatePath = "audio/{$levelCode}/lesson_{$lNumStr}/bt/{$rawFilename}";
            $fullCandidate = storage_path("app/public/hsk_media/{$candidatePath}");

            if (File::exists($fullCandidate)) {
                $p->audio_path = $candidatePath;
                $p->save();
                $updatedDbCount++;
            }
        }
        $this->info("✓ Updated {$updatedDbCount} database records to canonical hierarchical paths.");

        $this->newLine();
        $this->info('🎉 HSK AUDIO STANDARDIZATION COMPLETED SUCCESSFULLY!');
        return Command::SUCCESS;
    }
}
