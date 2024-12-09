<?php


use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middleware configurations can be added here
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->respond(function (Response $response) {
            // Check if the response is an unauthorized error
            if ($response->getStatusCode() === 401) {
                // Return a custom JSON response for unauthorized requests
                return response()->json(['message' => 'Access is denied due to invalid credentials. Please log in to continue.'], 401);
            }
            return $response;
        });
    })->create();
