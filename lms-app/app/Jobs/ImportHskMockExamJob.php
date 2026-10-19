<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\AdminSystemNotification;
use App\Services\Admin\HskMockExamService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ImportHskMockExamJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 2;

    /**
     * The maximum number of seconds the job can run.
     */
    public int $timeout = 600;

    /**
     * Create a new job instance.
     *
     * @param string $importBatchId Unique batch identifier for the import session
     * @param string $dataExtension Extension of data file (json or csv)
     * @param int $userId ID of admin or teacher who initiated the import
     */
    public function __construct(
        public readonly string $importBatchId,
        public readonly string $dataExtension,
        public readonly int $userId
    ) {}

    /**
     * Execute the job to import exam data and upload associated media.
     *
     * @param HskMockExamService $hskMockExamService
     * @return void
     */
    public function handle(HskMockExamService $hskMockExamService): void
    {
        $tempDir = storage_path("app/temp_imports/{$this->importBatchId}");
        $dataFilePath = "{$tempDir}/exam_data.{$this->dataExtension}";

        if (!File::exists($dataFilePath)) {
            Log::error("[ImportHskMockExamJob] Data file not found: {$dataFilePath}");
            return;
        }

        try {
            Log::info("[ImportHskMockExamJob] Processing exam import for batch {$this->importBatchId}");

            if ($this->dataExtension === 'csv') {
                $data = $hskMockExamService->parseCsvToData($dataFilePath);
            } else {
                $jsonContent = File::get($dataFilePath);
                $data = json_decode($jsonContent, true);
            }

            // Retrieve temporary media files
            $mediaDir = "{$tempDir}/media";
            $mediaFiles = File::exists($mediaDir) ? File::files($mediaDir) : [];

            // Perform exam import and cloud media upload
            $result = $hskMockExamService->importExam($data, $mediaFiles);

            Log::info("[ImportHskMockExamJob] Successfully imported exam {$result['exam_id']} with {$result['total_questions']} questions.");

            // Invalidate cached exam counts and levels summary
            Cache::forget('hsk_levels_with_counts');
            Cache::forget('hsk_total_exams_count');

            // Send notification to the user
            $user = User::find($this->userId);
            if ($user) {
                $user->notify(new AdminSystemNotification(
                    title: __('Nhập đề thi thành công'),
                    message: __('Đề thi :id đã được tải lên và sẵn sàng sử dụng (:count câu hỏi).', ['id' => $result['exam_id'], 'count' => $result['total_questions']]),
                    link: route(($user->role === User::ROLE_TEACHER ? 'teacher.' : 'admin.') . 'hsk-mock-exams.index'),
                    icon: 'check_circle',
                    iconColor: 'emerald'
                ));
            }
        } catch (Exception $e) {
            Log::error("[ImportHskMockExamJob] Error importing exam batch {$this->importBatchId}: " . $e->getMessage());

            $user = User::find($this->userId);
            if ($user) {
                $user->notify(new AdminSystemNotification(
                    title: __('Nhập đề thi thất bại'),
                    message: __('Không thể xử lý đề thi, vui lòng kiểm tra lại file tải lên hoặc thử lại sau.'),
                    link: route(($user->role === User::ROLE_TEACHER ? 'teacher.' : 'admin.') . 'hsk-mock-exams.index'),
                    icon: 'error',
                    iconColor: 'red'
                ));
            }
            throw $e;
        } finally {
            // Clean up all temporary files on local disk
            if (File::exists($tempDir)) {
                File::deleteDirectory($tempDir);
            }
        }
    }
}
