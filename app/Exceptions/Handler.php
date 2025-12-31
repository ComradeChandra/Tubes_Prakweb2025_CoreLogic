<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        // Kembalikan pesan yang ramah saat POST size melebihi batas server
        $this->renderable(function (PostTooLargeException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Ukuran unggahan melebihi batas server. Coba gunakan file yang lebih kecil atau hubungi admin.'
                ], 413);
            }

            return back()->withErrors(['file' => 'Ukuran unggahan melebihi batas server. Coba gunakan file yang lebih kecil atau hubungi admin.']);
        });
    }
}
