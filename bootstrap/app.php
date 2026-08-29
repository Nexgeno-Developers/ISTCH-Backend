<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\RedirectIfNotAuthenticated;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\AllowBackendAccess;
use App\Http\Middleware\ProtectForms;
use App\Http\Middleware\VerifyRecaptcha;
use App\Http\Middleware\TrackVisitors;
use App\Http\Middleware\SetApiCacheHeaders;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'stripe/webhook',
        ]);

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        $middleware->appendToGroup('auth.backend', [
            RedirectIfNotAuthenticated::class,
        ]);

        $middleware->appendToGroup('auth.guest', [
            RedirectIfAuthenticated::class,
        ]);

        $middleware->appendToGroup('auth.backend.access', [
            AllowBackendAccess::class,
        ]);

        $middleware->appendToGroup('protect.forms', [
            ProtectForms::class,
        ]);

        $middleware->appendToGroup('recaptcha', [
            VerifyRecaptcha::class,
        ]);

        $middleware->appendToGroup('api', [
            SetApiCacheHeaders::class,
        ]);

        $middleware->append([
            TrackVisitors::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
