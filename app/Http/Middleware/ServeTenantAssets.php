<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ServeTenantAssets
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the request is for a tenant asset
        if ($request->is('tenant/*/storage/*')) {
            $path = $request->path();
            $segments = explode('/', $path);
            
            // Extract tenant ID and file path
            $tenantId = $segments[1];
            $filePath = implode('/', array_slice($segments, 3));
            
            // Build the full path to the file
            $fullPath = storage_path("tenant{$tenantId}/app/public/{$filePath}");
            
            // Check if the file exists
            if (file_exists($fullPath)) {
                // Determine the MIME type
                $mimeType = mime_content_type($fullPath);
                
                // Set appropriate headers
                $headers = [
                    'Content-Type' => $mimeType,
                    'Cache-Control' => 'public, max-age=86400',
                ];
                
                // Return the file
                return response()->file($fullPath, $headers);
            }
        }
        
        return $next($request);
    }
}