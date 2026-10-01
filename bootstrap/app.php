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
    ->withMiddleware(function (Middleware $middleware): void {
        // In production the app only receives traffic from the Nginx reverse proxy,
        // so trust its X-Forwarded-* headers (real client IP, https scheme).
        $middleware->trustProxies(at: '*');

        // There is no /login page; the admin SPA shows its own lock screen.
        $middleware->redirectGuestsTo('/admin');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
