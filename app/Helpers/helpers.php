<?php

/**
 * Global helper functions for the application
 */

if (!function_exists('tenant_asset')) {
    /**
     * Generate a tenant-specific asset URL
     *
     * @param string $path
     * @return string
     */
    function tenant_asset($path)
    {
        if (empty($path)) {
            return '';
        }
        
        // If the path is already a URL, return it as is
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }
        
        // Get the current tenant
        $tenant = tenant();
        
        if (!$tenant) {
            // Fallback to regular asset if not in tenant context
            return asset('storage/' . $path);
        }
        
        // Check if the file exists in the tenant's storage
        $tenantPath = storage_path('tenant' . $tenant->id . '/app/public/' . $path);
        
        if (file_exists($tenantPath)) {
            // Return the tenant-specific URL
            return url('tenant/' . $tenant->id . '/storage/' . $path);
        }
        
        // Fallback to regular asset
        return asset('storage/' . $path);
    }
}

if (!function_exists('central_asset')) {
    /**
     * Generate a URL for an asset in the central storage
     *
     * @param string $path
     * @return string
     */
    function central_asset($path)
    {
        return asset('storage/' . $path);
    }
}