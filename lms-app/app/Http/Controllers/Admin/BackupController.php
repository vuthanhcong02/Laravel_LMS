<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessBackupJob;
use App\Services\BackupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    public function __construct(private readonly BackupService $backupService)
    {
    }

    public function index(): View
    {
        $settings    = $this->backupService->getSettings();
        $backupFiles = $this->backupService->getPaginatedBackupFiles(10);
        $allFiles    = $this->backupService->getBackupFiles();

        return view('portal.admin.backup.index', compact('settings', 'backupFiles', 'allFiles'));
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'enabled'     => 'boolean',
            'frequency'   => 'required|in:hourly,daily,weekly',
            'run_at'      => 'required_if:frequency,daily,weekly|date_format:H:i',
            'day_of_week' => 'required_if:frequency,weekly|integer|min:0|max:6',
            'max_backups' => 'required|integer|min:1|max:30',
        ]);

        $this->backupService->updateSettings([
            'enabled'     => $request->boolean('enabled'),
            'frequency'   => $validated['frequency'],
            'run_at'      => $validated['run_at'] ?? null,
            'day_of_week' => $validated['day_of_week'] ?? 0,
            'max_backups' => $validated['max_backups'],
        ]);

        return redirect()->route('admin.backup.index')
            ->with('success', 'Đã lưu cài đặt backup thành công!');
    }

    public function runNow(): RedirectResponse
    {
        ProcessBackupJob::dispatch(isManual: true);

        return redirect()->route('admin.backup.index')
            ->with('success', 'Đã gửi yêu cầu backup vào hàng chờ xử lý (Queue)!');
    }

    public function download(string $filename): StreamedResponse|RedirectResponse
    {
        try {
            return $this->backupService->download($filename);
        } catch (RuntimeException $e) {
            return redirect()->route('admin.backup.index')
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(string $filename): RedirectResponse
    {
        try {
            $this->backupService->delete($filename);
            return redirect()->route('admin.backup.index')
                ->with('success', 'Đã xóa file backup.');
        } catch (RuntimeException $e) {
            return redirect()->route('admin.backup.index')
                ->with('error', $e->getMessage());
        }
    }
}
