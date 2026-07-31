<?php

namespace App\Jobs;

use App\Models\BackupSetting;
use App\Services\BackupService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 600;

    public function __construct(public readonly bool $isManual = false)
    {
    }

    public function handle(BackupService $backupService): void
    {
        try {
            $log = $backupService->run($this->isManual);

            if ($log->status === 'success' && !$this->isManual) {
                $setting = BackupSetting::getCurrent();
                $setting->update(['last_run_at' => now()]);
            }
        } catch (Exception $e) {
            Log::error("[ProcessBackupJob] Backup job failed: " . $e->getMessage());
        }
    }
}
