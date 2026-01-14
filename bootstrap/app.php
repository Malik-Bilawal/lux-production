<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\IsReferralApproved;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            require __DIR__.'/../routes/referral.php';
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Alias your custom middleware
        $middleware->alias([
            'isReferralApproved' => IsReferralApproved::class,
            'checkPermission' => \App\Http\Middleware\CheckPermission::class,
            'superadmin' => \App\Http\Middleware\SuperAdminOnly::class, // ✅ add this line
        ]);

        // Example of adding middleware to a specific group (uncomment if needed)
        // $middleware->appendToGroup('web', \App\Http\Middleware\CheckRole::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Define your exception handling here
    })
    ->create();
;
