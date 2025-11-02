<?php

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\RateLimiter;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function()
        {
            RateLimiter::for(
                "api",
                function ($request) {
                    return Limit::perMinute(3)->by(
                        $request->user()?->id ?: $request->ip()
                    );
                }
            );
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {

    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function(Throwable $exceptions, $request) {
            if($exceptions instanceof ThrottleRequestsException)
            {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'You have exceeded the allowed number of requests. Please try again later.',
                ], 429);
            }

            return $exceptions;
        });
    })->create();
