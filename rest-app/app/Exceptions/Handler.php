<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

final class Handler extends ExceptionHandler
{
    /**
     * @param $request
     * @param \Throwable $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, \Throwable $e): Response
    {
        return $this->handleApiException($request, $e);
    }

    /**
     * @param $request
     * @param \Throwable $exception
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleApiException($request, \Throwable $exception): \Illuminate\Http\JsonResponse
    {
        $exception = $this->prepareException($exception);

        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return $this->customApiResponse($exception);
        }

        if ($exception instanceof HttpResponseException) {
            $exception = $exception->getResponse();
            return $this->customApiResponse($exception);
        }

        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            $exception = $this->unauthenticated($request, $exception);
            return $this->customApiResponse($exception);
        }

        return $this->customApiResponse($exception);
    }

    /**
     * @param $exception
     * @return \Illuminate\Http\JsonResponse
     */
    private function customApiResponse($exception): \Illuminate\Http\JsonResponse
    {
        $trace = null;
        $error = null;
        $statusCode = 500;
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        }

        if ($statusCode === 500 && isset($exception->status)) {
            $statusCode = $exception->status;
        }

        if (method_exists($exception, 'getMessage')) {
            $error = $message = $exception->getMessage();
        }

        if (config('app.env') !== 'production' && method_exists($exception, 'getTrace')) {
            $trace = $exception->getTrace();
        }

        $response['status'] = $statusCode;

        $response = match($statusCode) {
            400 => ['message' => 'Bad Request'],
            401 => ['message' => 'Unauthorized'],
            403 => ['message' => 'Forbidden'],
            404 => ['message' => 'Not Found'],
            405 => ['message' => 'Method Not Allowed'],
            422 => ['message' => 'Unprocessable Content', 'validation' => $exception->errors()],
            default => ['message' => 'Internal Server Error'],
        };

        if(! isset($response['error']) && $error !== null) {
            $response['error'] = $error;
        }

        if ($trace !== null) {
            $response['trace'] = $trace;
        }

        return response()->json($response, $statusCode);
    }

}
