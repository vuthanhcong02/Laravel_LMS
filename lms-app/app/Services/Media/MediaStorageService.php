<?php

namespace App\Services\Media;

use App\Models\Blog;
use App\Models\Course;
use App\Models\User;
use Aws\S3\S3Client;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

        try {
            return Storage::disk($this->disk)->temporaryUrl(
                $cleanPath,
                now()->addMinutes($expirationMinutes)
            );
        } catch (Throwable $e) {
            // Fallback to local storage if B2 is temporarily unreachable
            if (Storage::disk('public')->exists($cleanPath)) {
                return asset('storage/' . $cleanPath);
            }
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

        $this->deleteDirectory($directory);
        Storage::disk($this->disk)->putFileAs($directory, $file, $filename);

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

        $this->deleteDirectory($directory);
        Storage::disk($this->disk)->putFileAs($directory, $file, $filename);

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

        $this->deleteDirectory($directory);
        Storage::disk($this->disk)->putFileAs($directory, $file, $filename);

        return $fullPath;
    }

    /**
     * Upload lesson materials or teacher notes
     */
    public function uploadLessonFile(int $courseId, int $lessonId, UploadedFile $file, string $type = 'materials'): string
    {
        $directory = "courses/course_{$courseId}/lessons/lesson_{$lessonId}/{$type}";
        return $this->upload($file, $directory);
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
     * Delete an entire directory on B2
     */
    public function deleteDirectory(string $directory): bool
    {
        $cleanDir = $this->normalizePath($directory);
        try {
            return Storage::disk($this->disk)->deleteDirectory($cleanDir);
        } catch (Throwable $e) {
            return false;
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
