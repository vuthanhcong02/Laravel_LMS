<?php

namespace App\Jobs;

use App\Models\Lesson;
use App\Services\Media\MediaStorageService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadLessonMaterialsJob implements ShouldQueue
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
     * @param int $courseId Course ID
     * @param int $lessonId Lesson ID
     * @param string $tempFilePath Temporary file path on local disk
     * @param string $type Material type ('pdfs' or 'notes')
     * @param string|null $oldPathToDelete Previous file path to delete on cloud storage (if replacing)
     */
    public function __construct(
        public readonly int $courseId,
        public readonly int $lessonId,
        public readonly string $tempFilePath,
        public readonly string $type = 'pdfs',
        public readonly ?string $oldPathToDelete = null
    ) {}

    /**
     * Execute the job to upload material and update lesson model.
     *
     * @param MediaStorageService $mediaStorage
     * @return void
     */
    public function handle(MediaStorageService $mediaStorage): void
    {
        if (!File::exists($this->tempFilePath)) {
            Log::warning("[UploadLessonMaterialsJob] Temporary file does not exist: {$this->tempFilePath}");
            return;
        }

        try {
            Log::info("[UploadLessonMaterialsJob] Starting upload for {$this->type} in lesson #{$this->lessonId}");

            // Upload file to cloud storage
            $storedPath = $mediaStorage->uploadLessonFile(
                $this->courseId,
                $this->lessonId,
                $this->tempFilePath,
                $this->type
            );

            // Update path in Lesson model
            $lesson = Lesson::find($this->lessonId);
            if ($lesson) {
                if ($this->type === 'pdfs') {
                    $lesson->pdf_path = $storedPath;
                } elseif ($this->type === 'notes') {
                    $lesson->note_file_path = $storedPath;
                }
                $lesson->save();
                Log::info("[UploadLessonMaterialsJob] Successfully updated {$this->type} for lesson #{$this->lessonId}: {$storedPath}");
            }

            // Delete old file if replacing
            if (!empty($this->oldPathToDelete)) {
                $mediaStorage->delete($this->oldPathToDelete);
                if (Storage::disk('public')->exists($this->oldPathToDelete)) {
                    Storage::disk('public')->delete($this->oldPathToDelete);
                }
            }
        } catch (Exception $e) {
            Log::error("[UploadLessonMaterialsJob] Failed uploading {$this->type} for lesson #{$this->lessonId}: " . $e->getMessage());
            throw $e;
        } finally {
            // Clean up temporary local file
            if (File::exists($this->tempFilePath)) {
                File::delete($this->tempFilePath);
            }
        }
    }
}
