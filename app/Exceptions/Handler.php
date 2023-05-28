<?php

namespace App\Exceptions;

use App\Traits\ResponsesJson;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
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
            $e instanceof ValidationException => $this->validationExceptionResponse($e, $request),
            default => $this->renderExceptionResponse($request, $e),
        };
    }

    protected function validationExceptionResponse(ValidationException $exception, $request)
    {
        return $this->shouldReturnJson($request, $exception)
            ? $this->unprocessableResponse($exception->errors(), $exception->getMessage())
            : $this->invalid($request, $exception);
    }

    protected function renderExceptionResponse($request, Throwable $e)
    {
        return $this->shouldReturnJson($request, $e)
            ? $this->serverErrorResponse()
            : $this->prepareResponse($request, $e);
    }

    protected function invalidAbilityExceptionResponse($request, AuthorizationException $exception)
    {
        return $this->shouldReturnJson($request, $exception)
            ? $this->validationErrorResponse()
            : $this->renderExceptionResponse($request, $exception);
    }

    protected function authenticationExceptionResponse($request, AuthenticationException $exception)
    {
        return $this->shouldReturnJson($request, $exception)
            ? $this->unauthorizedResponse($exception->getMessage())
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }
}
