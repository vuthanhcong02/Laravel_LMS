<?php

namespace App\Jobs;

use App\Services\Media\MediaStorageService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PurgeCloudMediaDirectoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The maximum number of seconds the job can run.
     */
    public int $timeout = 300;

    /**
     * Create a new job instance.
     *
     * @param string $directory Target directory path on cloud storage
     */
    public function __construct(
        public readonly string $directory
    ) {}

    /**
     * Execute the job to purge cloud directory.
     *
     * @param MediaStorageService $mediaStorage
     * @return void
     */
    public function handle(MediaStorageService $mediaStorage): void
    {
        try {
            Log::info("[PurgeCloudMediaDirectoryJob] Starting purge for directory: {$this->directory}");
            $success = $mediaStorage->deleteDirectory($this->directory);

            if ($success) {
                Log::info("[PurgeCloudMediaDirectoryJob] Successfully purged directory: {$this->directory}");
            } else {
                Log::warning("[PurgeCloudMediaDirectoryJob] Directory not found or already deleted: {$this->directory}");
            }
        } catch (Exception $e) {
            Log::error("[PurgeCloudMediaDirectoryJob] Error purging directory {$this->directory}: " . $e->getMessage());
            throw $e;
        }
    }
}
