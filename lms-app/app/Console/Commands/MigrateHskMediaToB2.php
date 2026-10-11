<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Finder\SplFileInfo;

class MigrateHskMediaToB2 extends Command
{
    /**
     * Console command signature.
     *
     * @var string
     */
    protected $signature = 'storage:migrate-phase2 
                            {--only= : Synchronize specific type only (images, audio)} 
                            {--level= : Filter by specific HSK level (hsk1, hsk2, ...)} 
                            {--force : Overwrite existing files on B2} 
                            {--limit= : Limit number of files to process} 
                            {--dry-run : Scan and display statistics without uploading}';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize all HSK Media (Images and Audio) from local storage to Backblaze B2 (Phase 2)';

    /**
     * B2 storage disk name
     */
    protected string $disk = 'b2';

    /**
     * B2 prefix for HSK standard media
     */
    protected string $b2Prefix = 'media/hsk_standard';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $only = $this->option('only'); // images or audio
        $level = $this->option('level'); // hsk1..hsk6
        $force = (bool) $this->option('force');
        $isDryRun = (bool) $this->option('dry-run');

        $this->info("=========================================================");
        $this->info("🚀 STARTING HSK MEDIA MIGRATION TO BACKBLAZE B2 (PHASE 2)");
        if ($isDryRun) {
            $this->warn("⚠️  DRY-RUN MODE: No files will be uploaded.");
        }
        if ($only) {
            $this->info("📌 Filter type: Synchronizing [{$only}] only.");
        }
        if ($level) {
            $this->info("📌 Filter level: Synchronizing [{$level}] only.");
        }
        $this->info("=========================================================");

        $baseMediaDir = storage_path('app/public/hsk_media');

        if (!File::isDirectory($baseMediaDir)) {
            $this->error("Directory {$baseMediaDir} does not exist!");
            return Command::FAILURE;
        }

        // Collect list of files to upload
        $filesToUpload = [];

        if (!$only || $only === 'images') {
            $imagesDir = $baseMediaDir . '/images' . ($level ? "/{$level}" : '');
            if (File::isDirectory($imagesDir)) {
                $imageFiles = File::allFiles($imagesDir);
                foreach ($imageFiles as $f) {
                    $filesToUpload[] = [
                        'type' => 'images',
                        'file' => $f,
                    ];
                }
            }
        }

        if (!$only || $only === 'audio') {
            $audioDir = $baseMediaDir . '/audio' . ($level ? "/{$level}" : '');
            if (File::isDirectory($audioDir)) {
                $audioFiles = File::allFiles($audioDir);
                foreach ($audioFiles as $f) {
                    $filesToUpload[] = [
                        'type' => 'audio',
                        'file' => $f,
                    ];
                }
            }
        }

        $limit = $this->option('limit') ? (int) $this->option('limit') : null;
        if ($limit && $limit > 0) {
            $filesToUpload = array_slice($filesToUpload, 0, $limit);
        }

        $totalFiles = count($filesToUpload);
        $this->info("Found total of {$totalFiles} files to synchronize.");

        if ($totalFiles === 0) {
            $this->warn("No files need to be processed.");
            return Command::SUCCESS;
        }

        // Fetch existing files on B2 for fast existence check
        $existingB2Files = [];
        if (!$force && !$isDryRun) {
            $this->info("Checking existing files on B2...");
            try {
                $allB2 = Storage::disk($this->disk)->allFiles($this->b2Prefix);
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

        foreach ($filesToUpload as $item) {
            /** @var SplFileInfo $file */
            $file = $item['file'];
            $relToBase = str_replace('\\', '/', substr($file->getRealPath(), strlen($baseMediaDir) + 1));
            $b2Path = "{$this->b2Prefix}/{$relToBase}";

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

        $uploadedMB = round($uploadedBytes / (1024 * 1024), 2);

        $this->table(
            ['Metric', 'Value / Size'],
            [
                ['Total files scanned', $totalFiles],
                ['Uploaded successfully', $uploadedCount],
                ['Skipped (already on B2)', $skippedCount],
                ['Upload failed', $failedCount],
                ['Uploaded volume', "{$uploadedMB} MB"],
            ]
        );

        $this->info("✅ Phase 2 synchronization to Backblaze B2 completed!");
        return Command::SUCCESS;
    }
}
