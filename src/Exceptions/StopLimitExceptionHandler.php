<?php

namespace Omidrezasalari\StopLimit\Exceptions;

use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class StopLimitExceptionHandler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Throwable $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {

            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        return parent::render($request, $exception);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = collect($e->validator->errors()->getMessages())->values()->collapse();

        if ($request->expectsJson()) {
            return response()->json($errors, Response::HTTP_BAD_REQUEST);
        }
    }

}
