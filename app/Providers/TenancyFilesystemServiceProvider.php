<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;

class TenancyFilesystemServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register the tenant disk when in a tenant context
        if (function_exists('tenant') && tenant()) {
            $tenantId = tenant('id');
            
            // Register a custom disk for the current tenant
            Storage::extend('tenant', function ($app, $config) use ($tenantId) {
                $config['root'] = storage_path("tenant{$tenantId}/app/public");
                $config['url'] = url("tenant{$tenantId}/storage");
                
                return Storage::createLocalDriver($config);
            });
        }
    }
}