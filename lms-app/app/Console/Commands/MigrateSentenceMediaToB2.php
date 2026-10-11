<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Finder\SplFileInfo;

class MigrateSentenceMediaToB2 extends Command
{
    /**
     * Console command signature.
     *
     * @var string
     */
    protected $signature = 'storage:migrate-sentences 
                            {--level= : Filter by specific HSK level (hsk1, hsk2, ...)} 
                            {--force : Overwrite existing files on B2} 
                            {--limit= : Limit number of files to process} 
                            {--dry-run : Scan and display statistics without uploading}';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize all practice sentence audio files from local storage to Backblaze B2';

    /**
     * B2 storage disk name
     */
    protected string $disk = 'b2';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $level = $this->option('level'); // hsk1..hsk9
        $force = (bool) $this->option('force');
        $isDryRun = (bool) $this->option('dry-run');

        $this->info("=========================================================");
        $this->info("🚀 STARTING SENTENCE AUDIO MIGRATION TO BACKBLAZE B2");
        if ($isDryRun) {
            $this->warn("⚠️  DRY-RUN MODE: Scanning only, no files will be uploaded.");
        }
        if ($level) {
            $this->info("📌 Filter level: Synchronizing [{$level}] only.");
        }
        $this->info("=========================================================");

        $baseDir = storage_path('app/public/sentences');

        if (!File::isDirectory($baseDir)) {
            $this->error("Directory {$baseDir} does not exist!");
            return Command::FAILURE;
        }

        $searchDir = $level ? ($baseDir . '/' . strtolower($level)) : $baseDir;

        if (!File::isDirectory($searchDir)) {
            $this->error("Level directory {$searchDir} does not exist!");
            return Command::FAILURE;
        }

        $files = File::allFiles($searchDir);
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;
        if ($limit && $limit > 0) {
            $files = array_slice($files, 0, $limit);
        }

        $totalFiles = count($files);
        $this->info("Found total of {$totalFiles} audio files to process.");

        if ($totalFiles === 0) {
            $this->warn("No files need to be processed.");
            return Command::SUCCESS;
        }

        // Fetch existing files on B2 for fast existence check
        $existingB2Files = [];
        if (!$force && !$isDryRun) {
            $this->info("Checking existing files on B2...");
            try {
                $prefix = $level ? ('sentences/' . strtolower($level)) : 'sentences';
                $allB2 = Storage::disk($this->disk)->allFiles($prefix);
                $existingB2Files = array_flip($allB2);
                $this->info("Discovered " . count($existingB2Files) . " files already existing on B2.");
            } catch (\Throwable $e) {
                $this->warn("Unable to pre-scan B2 list, falling back to individual checks.");
            }
        }

        $uploadedCount = 0;
        $skippedCount = 0;
        $failedCount = 0;
        $uploadedBytes = 0;

        $bar = $this->output->createProgressBar($totalFiles);
        $bar->start();

        foreach ($files as $file) {
            /** @var SplFileInfo $file */
            $relToBase = str_replace('\\', '/', substr($file->getRealPath(), strlen($baseDir) + 1));
            $b2Path = "sentences/{$relToBase}";
            $fileSize = $file->getSize();

            if (!$force && !$isDryRun) {
                if (isset($existingB2Files[$b2Path])) {
                    $skippedCount++;
                    $bar->advance();
                    continue;
                }
            }

            if (!$isDryRun) {
                try {
                    $content = File::get($file->getRealPath());
                    $success = Storage::disk($this->disk)->put($b2Path, $content);

                    if ($success) {
                        $uploadedCount++;
                        $uploadedBytes += $fileSize;
                    } else {
                        $failedCount++;
                    }
                } catch (\Throwable $e) {
                    $failedCount++;
                }
            } else {
                $uploadedCount++;
                $uploadedBytes += $fileSize;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $uploadedMb = round($uploadedBytes / 1024 / 1024, 2);

        $this->info("=========================================================");
        $this->info("🏁 SENTENCE AUDIO MIGRATION SUMMARY:");
        $this->info("   - Total files scanned: {$totalFiles}");
        $this->info("   - Uploaded:            {$uploadedCount} files ({$uploadedMb} MB)");
        $this->info("   - Skipped (existing):  {$skippedCount} files");
        if ($failedCount > 0) {
            $this->error("   - Failed:              {$failedCount} files");
        }
        $this->info("=========================================================");

        return $failedCount === 0 ? Command::SUCCESS : Command::FAILURE;
    }
}
