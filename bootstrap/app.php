<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\EnsureUserIsActive;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => CheckRole::class,
            'active' => EnsureUserIsActive::class,
        ]);
        $middleware->appendToGroup('web', EnsureUserIsActive::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->expectsJson()) {
                return null;
            }

            if ($e instanceof TokenMismatchException) {
                return response()->view('errors.419', [], 419);
            }

            if ($e instanceof HttpExceptionInterface) {
                $status = $e->getStatusCode();

                if (view()->exists("errors.$status")) {
                    return response()->view("errors.$status", [], $status);
                }
            }

            if (! config('app.debug')) {
                return response()->view('errors.500', [], 500);
            }

            return null;
        });
    })->create();
