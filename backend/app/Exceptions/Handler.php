<?php

namespace App\Exceptions;

use App\Http\Traits\ApiResponseTrait;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;

class Handler extends ExceptionHandler
{
    use ApiResponseTrait;

    public function render($request, Throwable $exception)
    {
        // Validation errors
        if ($exception instanceof ValidationException) {
            return $this->error(
                'Validation failed',
                $exception->errors(),
                422
            );
        }

        // Authentication errors
        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            return $this->error(
                'Unauthenticated',
                null,
                401
            );
        }

        // Authorization / Forbidden
        if ($exception instanceof AuthorizationException) {
            return $this->error(
                'Forbidden',
                null,
                403
            );
        }

        // Model not found
        if ($exception instanceof ModelNotFoundException) {
            return $this->error(
                'Resource not found',
                null,
                404
            );
        }

        

        return $this->error(
            'Server error',
            null,
            500
        );
    }
}
