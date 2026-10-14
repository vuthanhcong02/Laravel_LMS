<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class FileController extends Controller
{
    /**
     * Whitelist of allowed folder prefixes for file viewing.
     */
    private const ALLOWED_PREFIXES = [
        'assignments/',
        'submissions/',
        'feedback_audio/',
    ];

    /**
     * View or download file from local storage or cloud B2 storage.
     */
    public function show(Request $request): Response
    {
        $path = $request->query('path');

        // Prevent path traversal attacks (../, ..\, null bytes)
        if (!$path || str_contains($path, '..') || str_contains($path, "\0")) {
            abort(403);
        }

        // Only allow whitelisted storage directories
        $allowed = collect(self::ALLOWED_PREFIXES)
            ->contains(fn($prefix) => str_starts_with($path, $prefix));

        abort_if(!$allowed, 403);

        // 1. Check B2 cloud disk first if configured
        try {
            if (config('filesystems.disks.b2.key') && (str_contains($path, 'assignment_') || Storage::disk('b2')->exists($path))) {
                $b2Url = Storage::disk('b2')->temporaryUrl($path, now()->addMinutes(60));
                return redirect()->away($b2Url);
            }
        } catch (Throwable $e) {
            Log::warning("FileController B2 check failed for [{$path}]: " . $e->getMessage());
        }

        // 2. Check local private disk (legacy files or fallback)
        if (Storage::disk('local')->exists($path)) {
            return response()->file(Storage::disk('local')->path($path));
        }

        // 3. Check local public disk (if moved or mirrored)
        if (Storage::disk('public')->exists($path)) {
            return response()->file(Storage::disk('public')->path($path));
        }

        // 4. Fallback attempt for B2 presigned redirect
        try {
            if (config('filesystems.disks.b2.key')) {
                $b2Url = Storage::disk('b2')->temporaryUrl($path, now()->addMinutes(60));
                return redirect()->away($b2Url);
            }
        } catch (Throwable $e) {
            Log::warning("FileController B2 temporary URL generation failed for [{$path}]: " . $e->getMessage());
        }

        abort(404);
    }
}
