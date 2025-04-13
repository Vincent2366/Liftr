<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckTenantStatus;
use App\Http\Middleware\DisableCsrfForTenants;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register your middleware aliases
        $middleware->alias([
            'check.tenant.status' => CheckTenantStatus::class,
        ]);
        
        // Add session middleware to the web group
        $middleware->appendToGroup('web', \Illuminate\Session\Middleware\StartSession::class);
        
        // Ensure CSRF protection is enabled for all routes except specific ones
        $middleware->validateCsrfTokens(except: [
            'api/login', // Keep this if you still need an API login endpoint
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();







