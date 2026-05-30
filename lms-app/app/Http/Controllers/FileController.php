<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileController extends Controller
{
    /**
     * Các thư mục được phép truy cập qua file viewer.
     */
    private const ALLOWED_PREFIXES = [
        'assignments/',
        'submissions/',
        'feedback_audio/',
    ];

    /**
     * View or download file from local storage.
     */
    public function show(Request $request): BinaryFileResponse
    {
        $path = $request->query('path');

        // Chặn path traversal (../, ..\, encoded variants)
        if (!$path || str_contains($path, '..') || str_contains($path, "\0")) {
            abort(403);
        }

        // Chỉ cho phép truy cập các thư mục được whitelist
        $allowed = collect(self::ALLOWED_PREFIXES)
            ->contains(fn($prefix) => str_starts_with($path, $prefix));

        abort_if(!$allowed, 403);

        if (!Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return response()->file(Storage::disk('local')->path($path));
    }
}
