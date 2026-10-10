<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'video_url',
        'record_url',
        'pdf_path',
        'note_file_path',
        'note_content',
        'order',
    ];

    /**
     * Course that owns this lesson.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Assignments associated with this lesson.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    /**
     * Public URL for lesson PDF slide/document.
     */
    public function getPdfUrlAttribute(): ?string
    {
        if (!$this->pdf_path) {
            return null;
        }

        if (str_starts_with($this->pdf_path, 'http://') || str_starts_with($this->pdf_path, 'https://')) {
            return $this->pdf_path;
        }

        return '/storage/' . ltrim($this->pdf_path, '/');
    }

    /**
     * Public URL for attached lesson notes/materials.
     */
    public function getNoteFileUrlAttribute(): ?string
    {
        if (!$this->note_file_path) {
            return null;
        }

        if (str_starts_with($this->note_file_path, 'http://') || str_starts_with($this->note_file_path, 'https://')) {
            return $this->note_file_path;
        }

        return '/storage/' . ltrim($this->note_file_path, '/');
    }

    /**
     * Effective video/record URL (prefers record_url with fallback to video_url).
     */
    public function getEffectiveRecordUrlAttribute(): ?string
    {
        return $this->record_url ?: $this->video_url;
    }

    /**
     * Resolve responsive embed URL from any Google Drive, YouTube, or video URL.
     */
    public static function resolveEmbedUrl(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        $url = trim($url);

        // 1. Google Drive: drive.google.com/file/d/{ID}/view -> /preview
        if (preg_match('/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return 'https://drive.google.com/file/d/' . $matches[1] . '/preview';
        }

        // Google Drive Folders: drive.google.com/drive/(u/0/)?folders/{ID}
        if (preg_match('/drive\.google\.com\/(?:drive\/(?:u\/\d+\/)?)?folders\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return 'https://drive.google.com/embeddedfolderview?id=' . $matches[1] . '#grid';
        }

        // Google Drive: drive.google.com/open?id={ID} or uc?id={ID}
        if (preg_match('/drive\.google\.com\/(?:open|uc)\?id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return 'https://drive.google.com/file/d/' . $matches[1] . '/preview';
        }

        // Google Docs / Presentations / Sheets
        if (preg_match('/docs\.google\.com\/(?:document|presentation|spreadsheets)\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return 'https://docs.google.com/viewer?srcid=' . $matches[1] . '&pid=explorer&efh=false&a=v&chrome=false&embedded=true';
        }

        // 2. YouTube: youtube.com/watch?v={ID} or youtu.be/{ID} or embed/{ID}
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches)) {
            return 'https://www.youtube-nocookie.com/embed/' . $matches[1] . '?autoplay=0&rel=0&modestbranding=1';
        }

        return $url;
    }

    /**
     * Get responsive embed URL for primary video.
     */
    public function getEmbedVideoUrlAttribute(): ?string
    {
        return static::resolveEmbedUrl($this->effective_record_url);
    }

    /**
     * Parse all video/record URLs if multiple are provided.
     */
    public function getVideoListAttribute(): array
    {
        $rawStrings = array_filter([$this->record_url, $this->video_url]);
        $urls = [];

        foreach ($rawStrings as $raw) {
            $trimmed = trim($raw);
            if (empty($trimmed)) continue;

            // Check if JSON
            if ((str_starts_with($trimmed, '[') && str_ends_with($trimmed, ']')) || (str_starts_with($trimmed, '{') && str_ends_with($trimmed, '}'))) {
                $decoded = json_decode($trimmed, true);
                if (is_array($decoded)) {
                    foreach ($decoded as $k => $item) {
                        if (is_string($item)) {
                            $urls[] = ['title' => is_string($k) ? $k : null, 'url' => trim($item)];
                        } elseif (is_array($item) && !empty($item['url'])) {
                            $urls[] = ['title' => $item['title'] ?? null, 'url' => trim($item['url'])];
                        }
                    }
                    continue;
                }
            }

            // Split by lines or commas
            $lines = preg_split('/[\r\n]+|,/', $trimmed);
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;

                // Support "Title: https://..." or "Phần 1 - https://..."
                if (preg_match('/^(.*?)(?:[:\-]\s*)(https?:\/\/[^\s]+)$/i', $line, $matches)) {
                    $title = trim($matches[1]);
                    $url = trim($matches[2]);
                    $urls[] = ['title' => $title ?: null, 'url' => $url];
                } elseif (preg_match('/(https?:\/\/[^\s]+)/i', $line, $matches)) {
                    $urls[] = ['title' => null, 'url' => trim($matches[1])];
                }
            }
        }

        $unique = [];
        $seen = [];
        $count = 1;

        foreach ($urls as $item) {
            $u = $item['url'];
            if (isset($seen[$u])) continue;
            $seen[$u] = true;

            $embedUrl = static::resolveEmbedUrl($u);
            $isGoogleDrive = str_contains($u, 'drive.google.com') || str_contains($u, 'docs.google.com');
            $isYoutube = str_contains($u, 'youtube.com') || str_contains($u, 'youtu.be');

            $unique[] = [
                'index' => $count,
                'title' => $item['title'] ?: __('Part :num', ['num' => $count]),
                'url' => $u,
                'embed_url' => $embedUrl,
                'is_google_drive' => $isGoogleDrive,
                'is_youtube' => $isYoutube,
            ];
            $count++;
        }

        return $unique;
    }

    /**
     * Check if video source is Google Drive.
     */
    public function getIsGoogleDriveAttribute(): bool
    {
        $url = $this->effective_record_url;
        return $url && (str_contains($url, 'drive.google.com') || str_contains($url, 'docs.google.com'));
    }

    /**
     * Check if video source is YouTube.
     */
    public function getIsYoutubeAttribute(): bool
    {
        $url = $this->effective_record_url;
        return $url && (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be'));
    }
}
