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
            'disable.csrf' => DisableCsrfForTenants::class,
            'check.tenant.status' => CheckTenantStatus::class,
        ]);
        
        // Add session middleware to the web group
        $middleware->appendToGroup('web', \Illuminate\Session\Middleware\StartSession::class);
        
        // Exclude specific routes from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'api/login',
            'refresh-csrf',
            'sanctum/csrf-cookie',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();






