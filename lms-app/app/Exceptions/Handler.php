<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (TokenMismatchException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang.'], 419);
            }
            return redirect()->route('home')->with('error', 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang và thử lại.');
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
