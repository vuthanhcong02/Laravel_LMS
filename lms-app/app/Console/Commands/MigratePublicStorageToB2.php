<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\Course;
use App\Models\User;
use App\Services\Media\MediaStorageService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Throwable;

class MigratePublicStorageToB2 extends Command
{
    /**
     * Console command signature.
     *
     * @var string
     */
    protected $signature = 'storage:migrate-phase1 {--dry-run : Scan and display files without uploading or modifying DB}';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Phase 1 public media (Avatars, Blog, Course, Pinyin) from local storage to Backblaze B2';

    /**
     * Execute the console command.
     */
    public function handle(MediaStorageService $mediaService): int
    {
        $isDryRun = $this->option('dry-run');

        $this->info("==================================================");
        $this->info("🚀 STARTING PHASE 1 MEDIA MIGRATION TO BACKBLAZE B2");
        if ($isDryRun) {
            $this->warn("⚠️  DRY-RUN MODE: Scanning only, no files will be written.");
        }
        $this->info("==================================================");

        // 1. Migrate user avatars
        $this->migrateUserAvatars($isDryRun);

        // 2. Migrate blog thumbnails
        $this->migrateBlogThumbnails($isDryRun);

        // 3. Migrate course thumbnails
        $this->migrateCourseThumbnails($isDryRun);

        // 4. Migrate Pinyin pronunciation audio
        $this->migratePinyinAudio($isDryRun);

        $this->newLine();
        $this->info("🎉 PHASE 1 MIGRATION COMPLETED SUCCESSFULLY!");
        return Command::SUCCESS;
    }

    /**
     * Migrate user avatars to: avatars/user_{id}/avatar_{time}.{ext}
     */
    protected function migrateUserAvatars(bool $isDryRun): void
    {
        $this->line("\n[1/4] Scanning user avatars...");
        $users = User::whereNotNull('avatar')->where('avatar', '!=', '')->get();

        $count = 0;
        foreach ($users as $user) {
            $oldPath = ltrim($user->avatar, '/');
            if (str_starts_with($oldPath, 'storage/')) {
                $oldPath = substr($oldPath, 8);
            }

            // Skip if it is already an external URL or already migrated to new B2 structure
            if (filter_var($oldPath, FILTER_VALIDATE_URL) || str_starts_with($oldPath, "avatars/user_{$user->id}/")) {
                continue;
            }

            $localFullPath = storage_path('app/public/' . $oldPath);
            if (!File::exists($localFullPath)) {
                $this->warn("  - User #{$user->id} ({$user->full_name}): File not found at {$oldPath}");
                continue;
            }

            $ext = pathinfo($localFullPath, PATHINFO_EXTENSION) ?: 'jpg';
            $newPath = "avatars/user_{$user->id}/avatar_" . time() . "_{$user->id}.{$ext}";

            $this->line("  - User #{$user->id}: <comment>{$oldPath}</comment> -> <info>{$newPath}</info>");

            if (!$isDryRun) {
                try {
                    $fileContent = File::get($localFullPath);
                    Storage::disk('b2')->put($newPath, $fileContent);
                    $user->avatar = $newPath;
                    $user->save();
                    $count++;
                } catch (Throwable $e) {
                    $this->error("    Upload error for User #{$user->id}: " . $e->getMessage());
                }
            } else {
                $count++;
            }
        }
        $this->info("✓ Processed {$count} user avatars.");
    }

    /**
     * Migrate blog thumbnails to: blogs/blog_{id}/thumbnail_{time}.{ext}
     */
    protected function migrateBlogThumbnails(bool $isDryRun): void
    {
        $this->line("\n[2/4] Scanning blog thumbnails...");
        $blogs = Blog::whereNotNull('thumbnail')->where('thumbnail', '!=', '')->get();

        $count = 0;
        foreach ($blogs as $blog) {
            $oldPath = ltrim($blog->thumbnail, '/');
            if (str_starts_with($oldPath, 'storage/')) {
                $oldPath = substr($oldPath, 8);
            }

            if (filter_var($oldPath, FILTER_VALIDATE_URL) || str_starts_with($oldPath, "blogs/blog_{$blog->id}/")) {
                continue;
            }

            $localFullPath = storage_path('app/public/' . $oldPath);
            if (!File::exists($localFullPath)) {
                continue;
            }

            $ext = pathinfo($localFullPath, PATHINFO_EXTENSION) ?: 'jpg';
            $newPath = "blogs/blog_{$blog->id}/thumbnail_" . time() . ".{$ext}";

            $this->line("  - Blog #{$blog->id} ({$blog->title}): <comment>{$oldPath}</comment> -> <info>{$newPath}</info>");

            if (!$isDryRun) {
                try {
                    $fileContent = File::get($localFullPath);
                    Storage::disk('b2')->put($newPath, $fileContent);
                    $blog->thumbnail = $newPath;
                    $blog->save();
                    $count++;
                } catch (Throwable $e) {
                    $this->error("    Upload error for Blog #{$blog->id}: " . $e->getMessage());
                }
            } else {
                $count++;
            }
        }
        $this->info("✓ Processed {$count} blog thumbnails.");
    }

    /**
     * Migrate course thumbnails to: courses/course_{id}/thumbnail_{time}.{ext}
     */
    protected function migrateCourseThumbnails(bool $isDryRun): void
    {
        $this->line("\n[3/4] Scanning course thumbnails...");
        $courses = Course::whereNotNull('thumbnail')->where('thumbnail', '!=', '')->get();

        $count = 0;
        foreach ($courses as $course) {
            $oldPath = ltrim($course->thumbnail, '/');
            if (str_starts_with($oldPath, 'storage/')) {
                $oldPath = substr($oldPath, 8);
            }

            if (filter_var($oldPath, FILTER_VALIDATE_URL) || str_starts_with($oldPath, "courses/course_{$course->id}/")) {
                continue;
            }

            $localFullPath = storage_path('app/public/' . $oldPath);
            if (!File::exists($localFullPath)) {
                continue;
            }

            $ext = pathinfo($localFullPath, PATHINFO_EXTENSION) ?: 'jpg';
            $newPath = "courses/course_{$course->id}/thumbnail_" . time() . ".{$ext}";

            $this->line("  - Course #{$course->id} ({$course->title}): <comment>{$oldPath}</comment> -> <info>{$newPath}</info>");

            if (!$isDryRun) {
                try {
                    $fileContent = File::get($localFullPath);
                    Storage::disk('b2')->put($newPath, $fileContent);
                    $course->thumbnail = $newPath;
                    $course->save();
                    $count++;
                } catch (Throwable $e) {
                    $this->error("    Upload error for Course #{$course->id}: " . $e->getMessage());
                }
            } else {
                $count++;
            }
        }
        $this->info("✓ Processed {$count} course thumbnails.");
    }

    /**
     * Migrate Pinyin audio from storage/app/public/audio/pinyin/ to B2 media/pinyin/
     */
    protected function migratePinyinAudio(bool $isDryRun): void
    {
        $this->line("\n[4/4] Scanning Pinyin chart audio...");
        $localPinyinDir = storage_path('app/public/audio/pinyin');

        if (!File::isDirectory($localPinyinDir)) {
            $this->warn("  Local Pinyin audio directory not found at: {$localPinyinDir}");
            return;
        }

        $files = File::files($localPinyinDir);
        $totalFiles = count($files);
        $this->line("  Found <comment>{$totalFiles}</comment> Pinyin audio files.");

        if ($totalFiles === 0) {
            return;
        }

        $bar = $this->output->createProgressBar($totalFiles);
        $bar->start();

        $uploaded = 0;
        $skipped = 0;

        foreach ($files as $file) {
            $filename = $file->getFilename();
            $targetPath = "media/pinyin/{$filename}";

            if (!$isDryRun) {
                try {
                    // Skip if file already exists on B2 with identical size
                    if (Storage::disk('b2')->exists($targetPath)) {
                        $remoteSize = Storage::disk('b2')->size($targetPath);
                        if ($remoteSize === $file->getSize()) {
                            $skipped++;
                            $bar->advance();
                            continue;
                        }
                    }

                    Storage::disk('b2')->put($targetPath, File::get($file->getRealPath()));
                    $uploaded++;
                } catch (Throwable $e) {
                    // Continue to next file on error
                }
            } else {
                $uploaded++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✓ Finished Pinyin audio (Uploaded: {$uploaded}, Skipped: {$skipped}).");
    }
}
