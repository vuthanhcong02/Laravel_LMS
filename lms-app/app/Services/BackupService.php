<?php

namespace App\Services;

use App\Models\BackupLog;
use App\Models\BackupSetting;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupService
{
    private const BACKUP_DIR = 'backups';

    public function getSettings(): BackupSetting
    {
        return BackupSetting::getCurrent();
    }

    public function updateSettings(array $data): BackupSetting
    {
        $settings = $this->getSettings();
        $settings->update([
            'enabled'     => (bool) ($data['enabled'] ?? false),
            'frequency'   => $data['frequency'],
            'run_at'      => isset($data['run_at']) ? $data['run_at'] . ':00' : '02:00:00',
            'day_of_week' => $data['day_of_week'] ?? 0,
            'max_backups' => $data['max_backups'],
        ]);

        return $settings;
    }

    public function run(bool $isManual = false): BackupLog
    {
        $filename  = 'backup_' . now()->format('Y-m-d_H-i-s') . '.sql.gz';
        $directory = storage_path('app/' . self::BACKUP_DIR);
        $filepath  = $directory . '/' . $filename;

        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        try {
            $this->runMysqldump($filepath);

            $sizeBytes = file_exists($filepath) ? filesize($filepath) : 0;

            $log = BackupLog::create([
                'filename'   => $filename,
                'filepath'   => $filepath,
                'size_bytes' => $sizeBytes,
                'status'     => 'success',
                'message'    => null,
                'is_manual'  => $isManual,
            ]);

            Log::info("[BackupService] Backup success: {$filename} ({$sizeBytes} bytes)");

            $this->cleanOldBackups();

            return $log;
        } catch (Exception $e) {
            Log::error("[BackupService] Backup failed: " . $e->getMessage());

            return BackupLog::create([
                'filename'   => $filename,
                'filepath'   => null,
                'size_bytes' => 0,
                'status'     => 'failed',
                'message'    => $e->getMessage(),
                'is_manual'  => $isManual,
            ]);
        }
    }

    private function runMysqldump(string $outputPath): void
    {
        $host     = config('database.connections.mysql.host');
        $port     = config('database.connections.mysql.port');
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        $command = sprintf(
            'bash -c "set -o pipefail; mysqldump --host=%s --port=%s --user=%s --single-transaction --quick --lock-tables=false %s | gzip > %s"',
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($username),
            escapeshellarg($database),
            escapeshellarg($outputPath)
        );

        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $env = array_filter(
            array_merge($_ENV, $_SERVER, ['MYSQL_PWD' => (string) $password]),
            fn($v) => is_scalar($v)
        );

        $process = proc_open($command, $descriptors, $pipes, null, $env);

        if (!is_resource($process)) {
            throw new RuntimeException('Unable to spawn mysqldump process.');
        }

        fclose($pipes[0]);
        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        $returnCode = proc_close($process);

        if ($returnCode !== 0) {
            if (file_exists($outputPath)) {
                unlink($outputPath);
            }

            $errorMessage = trim($stderr ?: $stdout);
            throw new RuntimeException(
                'mysqldump failed (exit code ' . $returnCode . '): ' . $errorMessage
            );
        }
    }

    public function cleanOldBackups(): void
    {
        $settings   = BackupSetting::getCurrent();
        $maxBackups = $settings->max_backups;
        $directory  = storage_path('app/' . self::BACKUP_DIR);

        if (!is_dir($directory)) {
            return;
        }

        $files = glob($directory . '/*.sql.gz');

        if (!$files) {
            return;
        }

        usort($files, fn($a, $b) => filemtime($b) - filemtime($a));

        $toDelete = array_slice($files, $maxBackups);

        foreach ($toDelete as $file) {
            $basename = basename($file);
            unlink($file);
            BackupLog::where('filename', $basename)->delete();
            Log::info("[BackupService] Deleted old backup: {$basename}");
        }
    }

    public function getBackupFiles(): array
    {
        $directory = storage_path('app/' . self::BACKUP_DIR);

        if (!is_dir($directory)) {
            return [];
        }

        $files = glob($directory . '/*.sql.gz');

        if (!$files) {
            return [];
        }

        usort($files, fn($a, $b) => filemtime($b) - filemtime($a));

        return array_map(function ($file) {
            $bytes = filesize($file);
            return [
                'filename'   => basename($file),
                'filepath'   => $file,
                'size'       => $bytes,
                'size_human' => $this->formatBytes($bytes),
                'created_at' => date('Y-m-d H:i:s', filemtime($file)),
            ];
        }, $files);
    }

    public function getPaginatedBackupFiles(int $perPage = 10): LengthAwarePaginator
    {
        $allFiles = $this->getBackupFiles();
        $page     = Paginator::resolveCurrentPage('files_page') ?: 1;
        $total    = count($allFiles);
        $results  = array_slice($allFiles, ($page - 1) * $perPage, $perPage);

        return new LengthAwarePaginator(
            $results,
            $total,
            $perPage,
            $page,
            [
                'path'     => Paginator::resolveCurrentPath(),
                'pageName' => 'files_page',
            ]
        );
    }

    public function download(string $filename): StreamedResponse
    {
        $filename = basename($filename);
        $filepath = storage_path('app/' . self::BACKUP_DIR . '/' . $filename);

        if (!file_exists($filepath)) {
            throw new RuntimeException('Backup file does not exist.');
        }

        return response()->streamDownload(function () use ($filepath) {
            $handle = fopen($filepath, 'rb');
            while (!feof($handle)) {
                echo fread($handle, 8192);
                ob_flush();
                flush();
            }
            fclose($handle);
        }, $filename, [
            'Content-Type'        => 'application/gzip',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function delete(string $filename): void
    {
        $filename = basename($filename);
        $filepath = storage_path('app/' . self::BACKUP_DIR . '/' . $filename);

        if (!file_exists($filepath)) {
            throw new RuntimeException('Backup file does not exist.');
        }

        unlink($filepath);
        BackupLog::where('filename', $filename)->delete();

        Log::info("[BackupService] Admin deleted backup: {$filename}");
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }
}
