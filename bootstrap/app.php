<?php

use App\Http\Middleware\RoleMiddleware; 
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
        // Register your RoleMiddleware with an alias
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);

        // Keep any other existing middleware registrations within this closure
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Keep your existing exception handling within this closure
    })->create();