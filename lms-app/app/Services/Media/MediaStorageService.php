<?php

namespace App\Services\Media;

use App\Models\Blog;
use App\Models\Course;
use App\Models\HskMockExam;
use App\Models\User;
use Aws\S3\S3Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class MediaStorageService
{
    /**
     * Default disk name for Backblaze B2 storage
     */
    protected string $disk = 'b2';

    /**
     * Get active storage disk name
     */
    public function disk(): string
    {
        return $this->disk;
    }

    /**
     * Get B2 bucket name from configuration
     */
    public function bucket(): string
    {
        return config('filesystems.disks.b2.bucket', '');
    }

    /**
     * Get underlying AWS S3 Client for advanced B2 operations
     */
    public function getS3Client(): S3Client
    {
        return Storage::disk($this->disk)->getClient();
    }

    /* ═══════════════════════════════════════════════════════════════════════
     * 1. AUTHORIZATION & HEALTH CHECK
     * ═══════════════════════════════════════════════════════════════════════ */

    /**
     * Check authorization with Backblaze B2 and bucket access permissions.
     *
     * @return bool True if connection and authorization succeed
     */
    public function authorize(): bool
    {
        try {
            $client = $this->getS3Client();
            $client->headBucket(['Bucket' => $this->bucket()]);
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * Detailed health check for B2 connection
     *
     * @return array Status information: status, bucket, endpoint, latency_ms, error (if any)
     */
    public function healthCheck(): array
    {
        $start = microtime(true);
        $bucket = $this->bucket();
        $endpoint = config('filesystems.disks.b2.endpoint', '');

        try {
            $client = $this->getS3Client();
            $client->headBucket(['Bucket' => $bucket]);
            $latency = round((microtime(true) - $start) * 1000, 2);

            return [
                'status' => 'connected',
                'authorized' => true,
                'bucket' => $bucket,
                'endpoint' => $endpoint,
                'latency_ms' => $latency,
                'error' => null,
            ];
        } catch (Throwable $e) {
            $latency = round((microtime(true) - $start) * 1000, 2);
            return [
                'status' => 'error',
                'authorized' => false,
                'bucket' => $bucket,
                'endpoint' => $endpoint,
                'latency_ms' => $latency,
                'error' => $e->getMessage(),
            ];
        }
    }

    /* ═══════════════════════════════════════════════════════════════════════
     * 2. PRESIGNED URLS (GET & PUT)
     * ═══════════════════════════════════════════════════════════════════════ */

    /**
     * Generate secure Presigned GET URL for browser streaming and media preview.
     * Default expiration: 1440 minutes (24 hours).
     *
     * @param string|null $path Relative storage path
     * @param int $expirationMinutes Expiration time in minutes
     * @param string $default Fallback URL if path is empty or unavailable
     * @return string
     */
    public function url(?string $path, int $expirationMinutes = 1440, string $default = ''): string
    {
        if (empty($path)) {
            return $default;
        }

        // Return immediately if it is already a fully qualified external URL
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        $cleanPath = $this->normalizePath($path);

        // If file exists locally on public disk (legacy avatars, fallback storage, or local dev), serve directly
        if (Storage::disk('public')->exists($cleanPath)) {
            return asset('storage/' . $cleanPath);
        }

        try {
            return Storage::disk($this->disk)->temporaryUrl(
                $cleanPath,
                now()->addMinutes($expirationMinutes)
            );
        } catch (Throwable $e) {
            return $default;
        }
    }

    /**
     * Generate Presigned PUT URL for direct client-to-B2 uploads.
     * Reduces server CPU, RAM and bandwidth overhead for large files.
     *
     * @param string $path Target destination path on B2
     * @param int $expirationMinutes Expiration time in minutes (default 15 minutes)
     * @param string|null $contentType Expected MIME type
     * @return string Signed PUT upload URL
     */
    public function createPresignedUploadUrl(string $path, int $expirationMinutes = 15, ?string $contentType = null): string
    {
        $cleanPath = $this->normalizePath($path);
        $client = $this->getS3Client();

        $params = [
            'Bucket' => $this->bucket(),
            'Key' => $cleanPath,
        ];

        if ($contentType) {
            $params['ContentType'] = $contentType;
        }

        $command = $client->getCommand('PutObject', $params);
        $request = $client->createPresignedRequest($command, "+{$expirationMinutes} minutes");

        return (string) $request->getUri();
    }

    /* ═══════════════════════════════════════════════════════════════════════
     * 3. UPLOAD OPERATIONS
     * ═══════════════════════════════════════════════════════════════════════ */

    /**
     * General file upload helper for UploadedFile instances.
     *
     * @param UploadedFile $file Uploaded file instance
     * @param string $folder Target folder on B2 (e.g. 'avatars', 'lessons/pdfs')
     * @param string|null $customFilename Optional custom filename
     * @return string Relative stored path
     */
    public function upload(UploadedFile $file, string $folder, ?string $customFilename = null): string
    {
        $cleanFolder = trim($folder, '/');
        $extension = $file->getClientOriginalExtension() ?: 'bin';
        $filename = $customFilename ?: (Str::random(40) . '.' . $extension);
        $fullPath = "{$cleanFolder}/{$filename}";

        Storage::disk($this->disk)->putFileAs($cleanFolder, $file, $filename);

        return $fullPath;
    }

    /**
     * Upload raw binary content directly to B2.
     *
     * @param string $path Target file path
     * @param string $content File content
     * @param string|null $mimeType MIME type
     * @return bool
     */
    public function uploadRaw(string $path, string $content, ?string $mimeType = null): bool
    {
        $cleanPath = $this->normalizePath($path);
        $options = $mimeType ? ['ContentType' => $mimeType] : [];

        return Storage::disk($this->disk)->put($cleanPath, $content, $options);
    }

    /**
     * Upload stream resource to B2 (memory efficient for large files).
     *
     * @param string $path Target file path
     * @param resource $resource Stream resource
     * @return bool
     */
    public function uploadStream(string $path, $resource): bool
    {
        $cleanPath = $this->normalizePath($path);
        return Storage::disk($this->disk)->put($cleanPath, $resource);
    }

    /* ═══════════════════════════════════════════════════════════════════════
     * 4. LMS DOMAIN ENTITY HELPERS
     * ═══════════════════════════════════════════════════════════════════════ */

    /**
     * Upload user avatar with personalized path and clean up previous file:
     * avatars/user_{id}/avatar_{timestamp}.{extension}
     */
    public function uploadUserAvatar(User $user, UploadedFile $file): string
    {
        $directory = "avatars/user_{$user->id}";
        $extension = $file->getClientOriginalExtension() ?: 'webp';
        $filename = "avatar_" . time() . "_{$user->id}." . $extension;
        $fullPath = "{$directory}/{$filename}";

        // Clean up old local avatar files if exist
        try {
            if ($user->avatar && !str_starts_with($user->avatar, 'http') && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            if (Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->deleteDirectory($directory);
            }
        } catch (Throwable $e) {
            Log::warning("Could not clean old local avatar for user {$user->id}: " . $e->getMessage());
        }

        // Clean up previous avatar directory on B2
        try {
            $this->deleteDirectory($directory);
        } catch (Throwable $e) {
            Log::warning("Could not clean previous B2 avatar directory for user {$user->id}: " . $e->getMessage());
        }

        // Upload to B2 storage with automatic fallback to public local storage
        try {
            Storage::disk($this->disk)->putFileAs($directory, $file, $filename);
        } catch (Throwable $e) {
            Log::error("Failed to upload avatar to B2 for user {$user->id}: " . $e->getMessage() . ". Falling back to local storage.");
            Storage::disk('public')->putFileAs($directory, $file, $filename);
        }

        return $fullPath;
    }

    /**
     * Download avatar from external URL (e.g., Google OAuth) and upload to B2.
     *
     * @param User $user
     * @param string $url
     * @return string|null Relative stored path or null on failure
     */
    public function uploadAvatarFromUrl(User $user, string $url): ?string
    {
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        try {
            $response = Http::timeout(10)->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            ])->get($url);

            if (!$response->successful()) {
                Log::warning("Failed to download avatar from URL: {$url}, HTTP status: " . $response->status());
                return null;
            }

            $content = $response->body();
            if (empty($content)) {
                return null;
            }

            $directory = "avatars/user_{$user->id}";
            $filename = "avatar_" . time() . "_{$user->id}.jpg";
            $fullPath = "{$directory}/{$filename}";

            $this->deleteDirectory($directory);
            Storage::disk($this->disk)->put($fullPath, $content);

            return $fullPath;
        } catch (Throwable $e) {
            Log::error("Error saving avatar from URL for user {$user->id}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Upload blog thumbnail:
     * blogs/blog_{id}/thumbnail_{timestamp}.{extension}
     */
    public function uploadBlogThumbnail(Blog $blog, UploadedFile $file): string
    {
        $directory = "blogs/blog_{$blog->id}";
        $extension = $file->getClientOriginalExtension() ?: 'webp';
        $filename = "thumbnail_" . time() . "." . $extension;
        $fullPath = "{$directory}/{$filename}";

        // Clean up previous files on local and B2
        try {
            if ($blog->thumbnail && !str_starts_with($blog->thumbnail, 'http') && Storage::disk('public')->exists($blog->thumbnail)) {
                Storage::disk('public')->delete($blog->thumbnail);
            }
            if (Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->deleteDirectory($directory);
            }
            $this->deleteDirectory($directory);
        } catch (Throwable $e) {
            Log::warning("Could not clean old thumbnail for blog {$blog->id}: " . $e->getMessage());
        }

        // Upload to B2 with fallback to local storage
        try {
            Storage::disk($this->disk)->putFileAs($directory, $file, $filename);
        } catch (Throwable $e) {
            Log::error("Failed to upload blog thumbnail to B2: " . $e->getMessage() . ". Falling back to local storage.");
            Storage::disk('public')->putFileAs($directory, $file, $filename);
        }

        return $fullPath;
    }

    /**
     * Upload course thumbnail:
     * courses/course_{id}/thumbnail_{timestamp}.{extension}
     */
    public function uploadCourseThumbnail(Course $course, UploadedFile $file): string
    {
        $directory = "courses/course_{$course->id}";
        $extension = $file->getClientOriginalExtension() ?: 'webp';
        $filename = "thumbnail_" . time() . "." . $extension;
        $fullPath = "{$directory}/{$filename}";

        // Clean up previous files on local and B2
        try {
            if ($course->thumbnail && !str_starts_with($course->thumbnail, 'http') && Storage::disk('public')->exists($course->thumbnail)) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            if (Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->deleteDirectory($directory);
            }
            $this->deleteDirectory($directory);
        } catch (Throwable $e) {
            Log::warning("Could not clean old thumbnail for course {$course->id}: " . $e->getMessage());
        }

        // Upload to B2 with fallback to local storage
        try {
            Storage::disk($this->disk)->putFileAs($directory, $file, $filename);
        } catch (Throwable $e) {
            Log::error("Failed to upload course thumbnail to B2: " . $e->getMessage() . ". Falling back to local storage.");
            Storage::disk('public')->putFileAs($directory, $file, $filename);
        }

        return $fullPath;
    }

    /**
     * Upload lesson materials or teacher notes:
     * courses/course_{courseId}/lessons/lesson_{lessonId}/{type}/file_{timestamp}.{extension}
     */
    public function uploadLessonFile(int $courseId, int $lessonId, UploadedFile $file, string $type = 'pdfs'): string
    {
        $directory = "courses/course_{$courseId}/lessons/lesson_{$lessonId}/{$type}";
        $extension = $file->getClientOriginalExtension() ?: 'bin';
        $filename = $type . "_" . time() . "_" . Str::random(6) . "." . $extension;
        $fullPath = "{$directory}/{$filename}";

        try {
            Storage::disk($this->disk)->putFileAs($directory, $file, $filename);
        } catch (Throwable $e) {
            Log::error("Failed to upload lesson {$type} to B2: " . $e->getMessage() . ". Falling back to local storage.");
            Storage::disk('public')->putFileAs($directory, $file, $filename);
        }

        return $fullPath;
    }

    /**
     * Upload HSK Mock Exam audio or question image to Backblaze B2:
     * hsk_mock_exams/{HSK_LEVEL}/{EXAM_CODE}/{subfolder}/media_{timestamp}_{random}.{extension}
     *
     * @param HskMockExam|int|string $exam Exam instance, exam database ID, or exam code (e.g. 'H10000')
     * @param UploadedFile $file Uploaded media file
     * @param string $subfolder Subfolder name ('images' or 'audio')
     * @param string|null $hskLevel Optional HSK level override (e.g. 'HSK1')
     * @return string Relative stored path
     */
    public function uploadHskExamMedia(HskMockExam|int|string $exam, UploadedFile $file, string $subfolder = 'images', ?string $hskLevel = null): string
    {
        $examModel = null;
        $examCode = null;

        if ($exam instanceof HskMockExam) {
            $examModel = $exam;
        } elseif (is_numeric($exam) && (int) $exam > 0) {
            $examModel = HskMockExam::with('hskLevel')->find((int) $exam);
        } elseif (is_string($exam) && !empty(trim($exam))) {
            $examCode = strtoupper(trim($exam));
        }

        // Resolve exam code from model if available
        if ($examModel) {
            if (preg_match('/(H\d+)/i', $examModel->title, $matches)) {
                $examCode = strtoupper($matches[1]);
            } elseif (!empty($examModel->audio_file) && preg_match('#hsk_mock_exams/(?:[A-Za-z0-9_-]+/)?(H\d+)#i', $examModel->audio_file, $matches)) {
                $examCode = strtoupper($matches[1]);
            } else {
                $examCode = strtoupper($examModel->folder_name ?: ('exam_' . $examModel->id));
            }

            if (!$hskLevel && $examModel->hskLevel) {
                $hskLevel = strtoupper($examModel->hskLevel->level_code);
            }
        } elseif (!$examCode && is_numeric($exam)) {
            $examCode = 'EXAM_' . $exam;
        }

        if (!$examCode) {
            throw new InvalidArgumentException("Unable to determine exam code for media upload.");
        }

        // Standardize HSK level (e.g. HSK1, HSK2)
        if ($hskLevel) {
            $hskLevel = strtoupper($hskLevel);
        } elseif (preg_match('/^H(\d)/i', $examCode, $m)) {
            $hskLevel = 'HSK' . $m[1];
        } else {
            $hskLevel = 'HSK1';
        }

        // Normalize subfolder (default to 'images' if 'questions' was passed)
        $cleanSubfolder = ($subfolder === 'questions') ? 'images' : trim($subfolder, '/');

        $directory = "hsk_mock_exams/{$hskLevel}/{$examCode}/{$cleanSubfolder}";
        $extension = $file->getClientOriginalExtension() ?: 'bin';
        $filename = "media_" . time() . "_" . Str::random(8) . "." . $extension;
        $fullPath = "{$directory}/{$filename}";

        try {
            Storage::disk($this->disk)->putFileAs($directory, $file, $filename);
        } catch (Throwable $e) {
            Log::error("Failed to upload HSK exam media to B2: " . $e->getMessage() . ". Falling back to local storage.");
            Storage::disk('public')->putFileAs($directory, $file, $filename);
        }

        return $fullPath;
    }

    /**
     * Upload Teacher Quiz media (question images/audio or global exam audio):
     * quizzes/quiz_{quizId}/{subfolder}/media_{timestamp}.{extension}
     */
    public function uploadQuizMedia(int $quizId, UploadedFile $file, string $subfolder = 'questions'): string
    {
        $directory = $quizId > 0 ? "quizzes/quiz_{$quizId}/{$subfolder}" : "quizzes/{$subfolder}";
        $extension = $file->getClientOriginalExtension() ?: 'bin';
        $filename = "media_" . time() . "_" . Str::random(8) . "." . $extension;
        $fullPath = "{$directory}/{$filename}";

        try {
            Storage::disk($this->disk)->putFileAs($directory, $file, $filename);
        } catch (Throwable $e) {
            Log::error("Failed to upload quiz media to B2: " . $e->getMessage() . ". Falling back to local storage.");
            Storage::disk('public')->putFileAs($directory, $file, $filename);
        }

        return $fullPath;
    }

    /**
     * Upload student assignment submission:
     * assignments/assignment_{assignmentId}/submissions/user_{userId}/sub_{timestamp}.{extension}
     */
    public function uploadAssignmentSubmission(int $assignmentId, int $userId, UploadedFile $file): string
    {
        $directory = "assignments/assignment_{$assignmentId}/submissions/user_{$userId}";
        $extension = $file->getClientOriginalExtension() ?: 'bin';
        $filename = "sub_" . time() . "_" . Str::random(6) . "." . $extension;
        $fullPath = "{$directory}/{$filename}";

        try {
            Storage::disk($this->disk)->putFileAs($directory, $file, $filename);
        } catch (Throwable $e) {
            Log::error("Failed to upload assignment submission to B2: " . $e->getMessage() . ". Falling back to local storage.");
            Storage::disk('local')->putFileAs($directory, $file, $filename);
        }

        return $fullPath;
    }

    /**
     * Upload teacher feedback audio:
     * assignments/assignment_{assignmentId}/feedback/sub_{submissionId}/feedback_{timestamp}.mp3
     */
    public function uploadAssignmentFeedback(int $assignmentId, int $submissionId, UploadedFile $file): string
    {
        $directory = "assignments/assignment_{$assignmentId}/feedback/sub_{$submissionId}";
        $extension = $file->getClientOriginalExtension() ?: 'mp3';
        $filename = "feedback_" . time() . "." . $extension;
        $fullPath = "{$directory}/{$filename}";

        try {
            Storage::disk($this->disk)->putFileAs($directory, $file, $filename);
        } catch (Throwable $e) {
            Log::error("Failed to upload assignment feedback audio to B2: " . $e->getMessage() . ". Falling back to local storage.");
            Storage::disk('local')->putFileAs($directory, $file, $filename);
        }

        return $fullPath;
    }

    /**
     * Upload teacher feedback attachment:
     * assignments/assignment_{assignmentId}/feedback/sub_{submissionId}/att_{timestamp}_{random}.{extension}
     */
    public function uploadAssignmentFeedbackAttachment(int $assignmentId, int $submissionId, UploadedFile $file): string
    {
        $directory = "assignments/assignment_{$assignmentId}/feedback/sub_{$submissionId}";
        $extension = $file->getClientOriginalExtension() ?: 'bin';
        $filename = "att_" . time() . "_" . Str::random(6) . "." . $extension;
        $fullPath = "{$directory}/{$filename}";

        try {
            Storage::disk($this->disk)->putFileAs($directory, $file, $filename);
        } catch (Throwable $e) {
            Log::error("Failed to upload assignment feedback attachment to B2: " . $e->getMessage() . ". Falling back to local storage.");
            Storage::disk('local')->putFileAs($directory, $file, $filename);
        }

        return $fullPath;
    }

    /**
     * Upload teacher assignment material / attachment:
     * assignments/assignment_{assignmentId}/materials/mat_{timestamp}_{random}.{extension}
     */
    public function uploadAssignmentMaterial(int $assignmentId, UploadedFile $file): string
    {
        $directory = "assignments/assignment_{$assignmentId}/materials";
        $extension = $file->getClientOriginalExtension() ?: 'bin';
        $filename = "mat_" . time() . "_" . Str::random(6) . "." . $extension;
        $fullPath = "{$directory}/{$filename}";

        try {
            Storage::disk($this->disk)->putFileAs($directory, $file, $filename);
        } catch (Throwable $e) {
            Log::error("Failed to upload assignment material to B2: " . $e->getMessage() . ". Falling back to local storage.");
            Storage::disk('local')->putFileAs($directory, $file, $filename);
        }

        return $fullPath;
    }

    /**
     * Upload support ticket attachment:
     * support/ticket_{ticketId}/attachment_{timestamp}.{extension}
     */
    public function uploadSupportAttachment(int $ticketId, UploadedFile $file): string
    {
        $directory = "support/ticket_{$ticketId}";
        $extension = $file->getClientOriginalExtension() ?: 'png';
        $filename = "attachment_" . time() . "_" . Str::random(6) . "." . $extension;
        $fullPath = "{$directory}/{$filename}";

        try {
            Storage::disk($this->disk)->putFileAs($directory, $file, $filename);
        } catch (Throwable $e) {
            Log::error("Failed to upload support attachment to B2: " . $e->getMessage() . ". Falling back to local storage.");
            Storage::disk('public')->putFileAs($directory, $file, $filename);
        }

        return $fullPath;
    }

    /**
     * Retrieve user avatar URL with automatic fallback to UI Avatars.
     */
    public function avatarUrl(?string $path, string $name = 'User'): string
    {
        $default = 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&color=FFFFFF&background=e07a5f';

        if (empty($path)) {
            return $default;
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        return $this->url($path, 1440, $default);
    }

    /**
     * Retrieve Pinyin pronunciation audio URL from B2.
     */
    public function pinyinAudioUrl(string $filename): string
    {
        $cleanName = basename($filename);
        $path = 'media/pinyin/' . $cleanName;

        return $this->url($path, 1440, asset('storage/audio/pinyin/' . $cleanName));
    }

    /**
     * Retrieve HSK lesson media (image or audio) from B2.
     */
    public function hskMediaUrl(?string $path, int $expirationMinutes = 1440): string
    {
        if (empty($path)) {
            return '';
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        $cleanPath = $this->normalizePath($path);

        if (str_starts_with($cleanPath, 'hsk_media/')) {
            $cleanPath = substr($cleanPath, 10);
        }

        $b2Path = 'media/hsk_standard/' . $cleanPath;

        return $this->url($b2Path, $expirationMinutes, asset('storage/hsk_media/' . $cleanPath));
    }

    /* ═══════════════════════════════════════════════════════════════════════
     * 5. READ & DOWNLOAD OPERATIONS
     * ═══════════════════════════════════════════════════════════════════════ */

    /**
     * Read file content as string
     */
    public function get(string $path): ?string
    {
        $cleanPath = $this->normalizePath($path);
        try {
            return Storage::disk($this->disk)->get($cleanPath);
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * Read stream resource of a file
     *
     * @param string $path
     * @return resource|null
     */
    public function readStream(string $path)
    {
        $cleanPath = $this->normalizePath($path);
        try {
            return Storage::disk($this->disk)->readStream($cleanPath);
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * Stream download file to client browser
     */
    public function download(string $path, ?string $name = null, array $headers = []): StreamedResponse
    {
        $cleanPath = $this->normalizePath($path);
        return Storage::disk($this->disk)->download($cleanPath, $name, $headers);
    }

    /* ═══════════════════════════════════════════════════════════════════════
     * 6. FILE & FOLDER MANAGEMENT
     * ═══════════════════════════════════════════════════════════════════════ */

    /**
     * Check if a file exists on B2
     */
    public function exists(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }
        $cleanPath = $this->normalizePath($path);
        try {
            return Storage::disk($this->disk)->exists($cleanPath);
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * Get file size in bytes
     */
    public function size(string $path): int
    {
        $cleanPath = $this->normalizePath($path);
        try {
            return Storage::disk($this->disk)->size($cleanPath);
        } catch (Throwable $e) {
            return 0;
        }
    }

    /**
     * Get file MIME type
     */
    public function mimeType(string $path): ?string
    {
        $cleanPath = $this->normalizePath($path);
        try {
            return Storage::disk($this->disk)->mimeType($cleanPath);
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * Get last modified timestamp
     */
    public function lastModified(string $path): int
    {
        $cleanPath = $this->normalizePath($path);
        try {
            return Storage::disk($this->disk)->lastModified($cleanPath);
        } catch (Throwable $e) {
            return 0;
        }
    }

    /**
     * Delete a single file on B2
     */
    public function delete(?string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        $cleanPath = $this->normalizePath($path);
        try {
            return Storage::disk($this->disk)->delete($cleanPath);
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * Delete multiple files on B2
     *
     * @param array $paths
     * @return bool
     */
    public function deleteMultiple(array $paths): bool
    {
        $cleanPaths = array_map([$this, 'normalizePath'], array_filter($paths));
        try {
            return Storage::disk($this->disk)->delete($cleanPaths);
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * Delete an entire directory on B2 (permanently purging all versions and delete markers)
     */
    public function deleteDirectory(string $directory): bool
    {
        $cleanDir = $this->normalizePath($directory);
        if (empty($cleanDir) || $cleanDir === '.' || $cleanDir === '/') {
            Log::warning("Refusing to delete root or empty directory on {$this->disk}.");
            return false;
        }

        try {
            // For B2 / S3 versioned buckets: purge all object versions and delete markers
            // to prevent leaving hidden versions and asterisk (*) in B2 console
            if (in_array($this->disk, ['b2', 's3'])) {
                $client = $this->getS3Client();
                $bucket = $this->bucket();
                $prefix = rtrim($cleanDir, '/') . '/';

                $keyMarker = null;
                $versionIdMarker = null;

                do {
                    $params = [
                        'Bucket' => $bucket,
                        'Prefix' => $prefix,
                    ];
                    if ($keyMarker) {
                        $params['KeyMarker'] = $keyMarker;
                    }
                    if ($versionIdMarker) {
                        $params['VersionIdMarker'] = $versionIdMarker;
                    }

                    $res = $client->listObjectVersions($params);

                    $objectsToDelete = [];
                    if (!empty($res->get('Versions'))) {
                        foreach ($res->get('Versions') as $v) {
                            $objectsToDelete[] = ['Key' => $v['Key'], 'VersionId' => $v['VersionId']];
                        }
                    }
                    if (!empty($res->get('DeleteMarkers'))) {
                        foreach ($res->get('DeleteMarkers') as $dm) {
                            $objectsToDelete[] = ['Key' => $dm['Key'], 'VersionId' => $dm['VersionId']];
                        }
                    }

                    if (!empty($objectsToDelete)) {
                        $client->deleteObjects([
                            'Bucket' => $bucket,
                            'Delete' => ['Objects' => $objectsToDelete],
                        ]);
                    }

                    $isTruncated = $res->get('IsTruncated');
                    $keyMarker = $res->get('NextKeyMarker');
                    $versionIdMarker = $res->get('NextVersionIdMarker');
                } while ($isTruncated);
            }

            return Storage::disk($this->disk)->deleteDirectory($cleanDir);
        } catch (Throwable $e) {
            Log::error("Failed to purge directory {$cleanDir} on {$this->disk}: " . $e->getMessage());
            try {
                return Storage::disk($this->disk)->deleteDirectory($cleanDir);
            } catch (Throwable $ex) {
                return false;
            }
        }
    }

    /**
     * List all files in a directory on B2
     *
     * @param string $directory
     * @return array
     */
    public function allFiles(string $directory = ''): array
    {
        $cleanDir = $this->normalizePath($directory);
        try {
            return Storage::disk($this->disk)->allFiles($cleanDir);
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Copy a file on B2
     */
    public function copy(string $from, string $to): bool
    {
        try {
            return Storage::disk($this->disk)->copy($this->normalizePath($from), $this->normalizePath($to));
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * Move or rename a file on B2
     */
    public function move(string $from, string $to): bool
    {
        try {
            return Storage::disk($this->disk)->move($this->normalizePath($from), $this->normalizePath($to));
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * Normalize relative path (remove leading slash and storage/ prefix)
     */
    protected function normalizePath(string $path): string
    {
        $clean = ltrim(trim($path), '/');
        if (str_starts_with($clean, 'storage/')) {
            $clean = substr($clean, 8);
        }
        return $clean;
    }
}
