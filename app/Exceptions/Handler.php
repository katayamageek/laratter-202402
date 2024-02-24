<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
// 🔽 追加
use Illuminate\Auth\AuthenticationException;

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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    // 🔽 追加
    public function render($request, Throwable $exception)
    {
        // 未認証の場合で．．．
        if ($exception instanceof AuthenticationException) {
            // APIリクエストの場合はJSONレスポンスを返す
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
        }

        // Webの場合は親クラスのrender()を呼び出す
        return parent::render($request, $exception);
    }
}
