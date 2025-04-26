<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e) {
            $statusCode = match ($e::class) {
                \Illuminate\Validation\ValidationException::class => 422,
                Exception::class => 400,
                default => 500
            };

            $response = [
                'status' => $statusCode,
                'message' => match($statusCode) {
                    400 => 'Bad Request',
                    401 => 'Unauthorized',
                    403 => 'Forbidden',
                    404 => 'Not Found',
                    405 => 'Method Not Allowed',
                    422 => 'Unprocessable Content',
                    default => 'Internal Server Error',
                },
            ];

            if (method_exists($e, 'getMessage')) {
                $response['error'] = $e->getMessage();
            }

            if ($e instanceof \Illuminate\Validation\ValidationException) {
                $response['validation'] = $e->errors();
            }

            if (config('app.env') !== 'production') {
                $response['trace'] = $e->getTrace();
            }

            return response()->json($response, $statusCode);
        });
    })->create();
