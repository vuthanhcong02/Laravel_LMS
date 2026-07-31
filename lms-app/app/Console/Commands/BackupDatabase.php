<?php

namespace App\Console\Commands;

use App\Models\BackupSetting;
use App\Services\BackupService;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class BackupDatabase extends Command
{
    protected $signature = 'backup:database {--force : Force run backup regardless of schedule}';

    protected $description = 'Backup database automatically based on settings schedule or manually (--force)';

    public function __construct(private readonly BackupService $backupService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $isForce  = $this->option('force');
        $settings = BackupSetting::getCurrent();

        if (!$isForce && !$settings->shouldRunNow()) {
            $this->line('[BackupDatabase] Not scheduled time. Skipping.');
            return self::SUCCESS;
        }

        if (!$isForce && !$settings->enabled) {
            $this->line('[BackupDatabase] Automatic backup is disabled. Skipping.');
            return self::SUCCESS;
        }

        $this->info('[BackupDatabase] Starting backup...');

        try {
            $log = $this->backupService->run(isManual: false);

            if ($log->status === 'success') {
                $this->info("[BackupDatabase] Backup successful: {$log->filename} ({$log->human_size})");
                return self::SUCCESS;
            }

            $this->error("[BackupDatabase] Backup failed: {$log->message}");
            return self::FAILURE;
        } catch (Exception $e) {
            $this->error('[BackupDatabase] Unknown error: ' . $e->getMessage());
            Log::error('[BackupDatabase] ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
