<?php

namespace App\Console\Commands;

use App\Models\HskLessonPracticeQuestion;
use App\Models\HskLessonPracticeSection;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class StandardizeHskImagePaths extends Command
{
    /**
     * Console command signature.
     *
     * @var string
     */
    protected $signature = 'hsk:standardize-images {--dry-run : Scan and display statistics without downloading or modifying database} {--force : Force re-download from source server even if file exists}';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Standardize HSK exercise image paths to images/{level}/bt/{file}, download missing images and deduplicate';

    /**
     * Base remote URL for exercise images from source.
     */
    protected string $remoteImageBase = 'https://media.tiengtrungbotui.com/%s/bt/image/%s';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = (bool) $this->option('dry-run');
        $force = (bool) $this->option('force');

        $this->info("=========================================================");
        $this->info("🚀 STARTING HSK EXERCISE IMAGE PATH STANDARDIZATION");
        if ($isDryRun) {
            $this->warn("⚠️  DRY-RUN MODE: No files or database records will be modified.");
        }
        $this->info("=========================================================");

        $baseMediaDir = storage_path('app/public/hsk_media');
        $oldImagesDir = $baseMediaDir . '/images';

        // 1. Collect all sections and questions with image_path
        $sections = HskLessonPracticeSection::whereNotNull('image_path')
            ->with('practice.lesson.level')
            ->get();

        $questions = HskLessonPracticeQuestion::whereNotNull('image_path')
            ->with('section.practice.lesson.level')
            ->get();

        $this->info("Found {$sections->count()} sections and {$questions->count()} questions with images.");

        // Image task list: key = "{levelCode}/{fileName}"
        $imageTasks = [];

        foreach ($sections as $sec) {
            $levelCode = $sec->practice?->lesson?->level?->level_code;
            if (!$levelCode) {
                continue;
            }
            $fileName = basename($sec->image_path);
            $key = "{$levelCode}/{$fileName}";
            if (!isset($imageTasks[$key])) {
                $imageTasks[$key] = [
                    'level' => $levelCode,
                    'file' => $fileName,
                    'sections' => [],
                    'questions' => [],
                ];
            }
            $imageTasks[$key]['sections'][] = $sec;
        }

        foreach ($questions as $ques) {
            $levelCode = $ques->section?->practice?->lesson?->level?->level_code;
            if (!$levelCode) {
                continue;
            }
            $fileName = basename($ques->image_path);
            $key = "{$levelCode}/{$fileName}";
            if (!isset($imageTasks[$key])) {
                $imageTasks[$key] = [
                    'level' => $levelCode,
                    'file' => $fileName,
                    'sections' => [],
                    'questions' => [],
                ];
            }
            $imageTasks[$key]['questions'][] = $ques;
        }

        $this->info("Total level-scoped image tasks to process: " . count($imageTasks));

        $downloadedCount = 0;
        $copiedCount = 0;
        $existedCount = 0;
        $failedCount = 0;
        $dbUpdatedSections = 0;
        $dbUpdatedQuestions = 0;

        $bar = $this->output->createProgressBar(count($imageTasks));
        $bar->start();

        foreach ($imageTasks as $task) {
            $level = $task['level'];
            $file = $task['file'];

            $targetRelativeDir = "images/{$level}/bt";
            $targetDir = "{$baseMediaDir}/{$targetRelativeDir}";
            $targetPath = "{$targetDir}/{$file}";
            $newDbPath = "{$targetRelativeDir}/{$file}";

            // Check physical file status
            $needFetch = $force || !File::exists($targetPath) || File::size($targetPath) === 0;

            if ($needFetch) {
                if (!$isDryRun) {
                    File::ensureDirectoryExists($targetDir);

                    // Prefer downloading from source server to get exact level-specific image
                    $remoteUrl = sprintf($this->remoteImageBase, $level, $file);
                    $downloadSuccess = false;

                    try {
                        $response = Http::timeout(10)->get($remoteUrl);
                        if ($response->successful() && strlen($response->body()) > 0) {
                            File::put($targetPath, $response->body());
                            $downloadSuccess = true;
                            $downloadedCount++;
                        }
                    } catch (\Throwable $e) {
                        $downloadSuccess = false;
                    }

                    // Fallback to local flat directory if remote download fails
                    if (!$downloadSuccess) {
                        $flatLocalPath = "{$oldImagesDir}/{$file}";
                        if (File::exists($flatLocalPath) && File::size($flatLocalPath) > 0) {
                            File::copy($flatLocalPath, $targetPath);
                            $copiedCount++;
                        } else {
                            $failedCount++;
                            $this->newLine();
                            $this->error("Image not found: [{$level}] {$file}");
                        }
                    }
                } else {
                    $downloadedCount++;
                }
            } else {
                $existedCount++;
            }

            // Update database records
            if (!$isDryRun) {
                foreach ($task['sections'] as $sec) {
                    if ($sec->image_path !== $newDbPath) {
                        $sec->update(['image_path' => $newDbPath]);
                        $dbUpdatedSections++;
                    }
                }

                foreach ($task['questions'] as $ques) {
                    if ($ques->image_path !== $newDbPath) {
                        $ques->update(['image_path' => $newDbPath]);
                        $dbUpdatedQuestions++;
                    }
                }
            } else {
                $dbUpdatedSections += count($task['sections']);
                $dbUpdatedQuestions += count($task['questions']);
            }

            $bar->advance();
            // Throttle 20ms to prevent rate limiting from source server
            usleep(20000);
        }

        $bar->finish();
        $this->newLine(2);

        // Display summary table
        $this->table(
            ['Metric', 'Quantity'],
            [
                ['Total images by level', count($imageTasks)],
                ['Images already existing in canonical folder', $existedCount],
                ['Images downloaded from source server', $downloadedCount],
                ['Images copied from flat folder (fallback)', $copiedCount],
                ['Images missing or failed', $failedCount],
                ['Section records updated in DB', $dbUpdatedSections],
                ['Question records updated in DB', $dbUpdatedQuestions],
            ]
        );

        $this->info("✅ HSK exercise image standardization completed successfully!");
        return Command::SUCCESS;
    }
}
