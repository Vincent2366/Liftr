<?php

namespace App\Providers;

use App\Http\Middleware\SuperAdminMiddleware;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    // Existing code...

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        // Existing code...

        // Register the super admin middleware with the correct namespace
        $this->app['router']->aliasMiddleware('super_admin', \App\Http\Middleware\SuperAdminMiddleware::class);
    }
}


