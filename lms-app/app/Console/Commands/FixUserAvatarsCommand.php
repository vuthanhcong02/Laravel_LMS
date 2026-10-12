<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\Media\MediaStorageService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Throwable;

class FixUserAvatarsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:fix-avatars {--dry-run : Inspect records without applying changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix broken or external Google avatars by downloading them to B2 or resetting invalid ones to null';

    /**
     * Execute the console command.
     */
    public function handle(MediaStorageService $storageService): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $this->info('=== SCANNING USERS WITH EXTERNAL OR CORRUPTED AVATARS ===');
        if ($dryRun) {
            $this->warn('[DRY-RUN MODE ENABLED] No database or file changes will be made.');
        }

        $users = User::whereNotNull('avatar')
            ->where(function ($query) {
                $query->where('avatar', 'like', 'http%')
                    ->orWhere('avatar', 'like', '%googleusercontent.com%');
            })
            ->get();

        $total = $users->count();
        if ($total === 0) {
            $this->info('No users found with external or broken avatars.');
            return Command::SUCCESS;
        }

        $this->info("Found {$total} user(s) to process.");

        $migratedCount = 0;
        $resetCount = 0;
        $skippedCount = 0;

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($users as $user) {
            $avatarUrl = trim($user->avatar);

            // Check if the URL is a valid URL format
            if (!filter_var($avatarUrl, FILTER_VALIDATE_URL)) {
                $this->newLine();
                $this->warn("User #{$user->id} ({$user->email}): Avatar is an invalid URL format. Resetting to null.");

                if (!$dryRun) {
                    $user->avatar = null;
                    $user->save();
                }
                $resetCount++;
                $bar->advance();
                continue;
            }

            // Probe URL to check accessibility and content
            try {
                $response = Http::timeout(6)->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                ])->get($avatarUrl);

                $contentType = $response->header('Content-Type', '');
                $isValidImage = $response->successful() && str_contains(strtolower($contentType), 'image');

                if (!$isValidImage) {
                    $this->newLine();
                    $this->warn("User #{$user->id} ({$user->email}): URL returned status {$response->status()} (not an image). Resetting to null.");

                    if (!$dryRun) {
                        $user->avatar = null;
                        $user->save();
                    }
                    $resetCount++;
                } else {
                    if (!$dryRun) {
                        $storedPath = $storageService->uploadAvatarFromUrl($user, $avatarUrl);
                        if ($storedPath) {
                            $user->avatar = $storedPath;
                            $user->save();
                            $migratedCount++;
                        } else {
                            $this->newLine();
                            $this->error("User #{$user->id} ({$user->email}): Failed to save image to B2.");
                            $skippedCount++;
                        }
                    } else {
                        $migratedCount++;
                    }
                }
            } catch (Throwable $e) {
                $this->newLine();
                $this->warn("User #{$user->id} ({$user->email}): Request failed with error '{$e->getMessage()}'. Resetting to null.");

                if (!$dryRun) {
                    $user->avatar = null;
                    $user->save();
                }
                $resetCount++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Processed', $total],
                ['Migrated to B2', $migratedCount],
                ['Reset to Null (Clean Fallback)', $resetCount],
                ['Skipped / Failed', $skippedCount],
            ]
        );

        $this->info('Avatar scan and migration completed.');
        return Command::SUCCESS;
    }
}
