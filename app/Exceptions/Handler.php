<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
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

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // Handle MethodNotAllowed for logout GET requests
        if ($exception instanceof MethodNotAllowedHttpException) {
            if ($request->is('logout')) {
                return redirect()->route('invoices.index')
                    ->with('message', 'Please use the logout button to end your session.');
            }
        }

        // Handle AccessDeniedHttpException for unauthorized actions in API requests
        if ($request->expectsJson() && $exception instanceof AccessDeniedHttpException) {
            return response()->json([
                'status'  => 'error',
                'message' => 'You do not have permission to perform this action.'
            ], 403);
        }

        return parent::render($request, $exception);
    }
}
