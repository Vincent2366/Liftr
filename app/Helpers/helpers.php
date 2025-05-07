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
        
        // For profile images and other standard assets, check if they exist in the public directory
        if (strpos($path, 'img/') === 0) {
            $publicPath = public_path($path);
            if (file_exists($publicPath)) {
                return url($path);
            }
        }
        
        // Log the path for debugging
        \Log::info("tenant_asset called with path: {$path}");
        \Log::info("Tenant ID: {$tenant->id}");
        
        // Check if the file exists in the tenant storage
        $fullPath = storage_path("tenant{$tenant->id}/app/public/{$path}");
        \Log::info("Checking file at: {$fullPath}");
        
        if (file_exists($fullPath)) {
            \Log::info("File exists at path");
        } else {
            \Log::warning("File does not exist at path");
        }
        
        // Return the tenant-specific URL
        return url("tenant{$tenant->id}/storage/{$path}");
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


