<?php

namespace App\Exceptions;

use App\Traits\ResponsesJson;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse as HttpJsonResponse;
use Illuminate\Http\RedirectResponse as HttpRedirectResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
use Symfony\Component\HttpFoundation\Response as SymfonyHttpFoundationResponse;
use Throwable;

class Handler extends ExceptionHandler
{
    use ResponsesJson;

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

    public function report(Throwable $e)
    {
        //parent::report($exception);
    }

    public function render($request, Throwable $e)
    {
        Log::error(print_r([
            'file' => $e->getFile(),
            'class' => get_class($e),
            'line' => $e->getLine(),
            'message' => $e->getMessage()
        ], true));

        if (method_exists($e, 'render') && $response = $e->render($request)) {
            return Router::toResponse($request, $response);
        } elseif ($e instanceof Responsable) {
            return $e->toResponse($request);
        }

        if($e instanceof MissingAbilityException){
            return $this->invalidAbilityExceptionResponse($request, $e);
        }

        $e = $this->prepareException($this->mapException($e));

        if ($response = $this->renderViaCallbacks($request, $e)) {
            return $response;
        }

        return match (true) {
            $e instanceof HttpResponseException => $e->getResponse(),
            $e instanceof AuthenticationException => $this->authenticationExceptionResponse($request, $e),
            $e instanceof ValidationException => $this->validationExceptionResponse($request, $e),
            $e instanceof ThrottleRequestsException => $this->throttleRequestsExceptionResponse($request, $e),
            default => $this->renderExceptionResponse($request, $e),
        };
    }

    protected function invalidAbilityExceptionResponse($request, AuthorizationException $exception): HttpJsonResponse|SymfonyHttpFoundationResponse
    {
        return $this->shouldReturnJson($request, $exception)
            ? $this->validationErrorResponse()
            : $this->renderExceptionResponse($request, $exception);
    }

    protected function authenticationExceptionResponse($request, AuthenticationException $exception): HttpJsonResponse|HttpRedirectResponse
    {
        return $this->shouldReturnJson($request, $exception)
            ? $this->unauthorizedResponse($exception->getMessage())
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }

    protected function validationExceptionResponse($request, ValidationException $exception): HttpResponse|HttpJsonResponse
    {
        return $this->shouldReturnJson($request, $exception)
            ? $this->unprocessableResponse($exception->errors(), $exception->getMessage())
            : $this->invalid($request, $exception);
    }

    protected function throttleRequestsExceptionResponse($request, ThrottleRequestsException $exception): HttpResponse|HttpJsonResponse
    {
        return $this->shouldReturnJson($request, $exception)
            ? $this->tooManyRequestsResponse($exception->getMessage())
            : $this->renderExceptionResponse($request, $exception);
    }

    protected function renderExceptionResponse($request, Throwable $e): HttpJsonResponse|SymfonyHttpFoundationResponse
    {
        return $this->shouldReturnJson($request, $e)
            ? $this->serverErrorResponse()
            : $this->prepareResponse($request, $e);
    }
}
