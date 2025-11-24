<?php

namespace App\Exceptions;

use Throwable;
use Carbon\Carbon;
use App\Traits\HttpResponses;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
  use HttpResponses;
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


  public function render($request, Throwable $exception)
  {
    if ($request->is('api/*')) {
      if ($exception instanceof ModelNotFoundException) {
        $msg = __('messages.not_found');
      }

      if ($exception instanceof NotFoundHttpException) {
        $msg = __('messages.url_not_found');
      }

      if ($exception instanceof AuthenticationException) {
        return $this->unauthenticatedResponse();
      }

      if ($exception instanceof ThrottleRequestsException) {
        $retryAfter = (Carbon::now()->diffInMinutes(Carbon::parse($exception->getHeaders()['X-RateLimit-Reset']))) ?? 30;
        return $this->failureResponse(__('apis.throttle', ['minutes' => $retryAfter]));
      }


      return $this->response(
        'exception',
        $msg ?? $exception->getMessage(),
        config('app.debug') ? ['line' => $exception->getLine(), 'file' => $exception->getFile()] : [],
        500
      );
    }

    return parent::render($request, $exception);
  }
}
