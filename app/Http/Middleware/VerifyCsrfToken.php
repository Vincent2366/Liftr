<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Closure;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Exclude all routes on tenant subdomains
        '*',
    ];
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if this is a tenant domain request
        if (app()->bound('tenancy.tenant') && app('tenancy.tenant')) {
            // Skip CSRF verification completely for tenant domains
            return $next($request);
        }
        
        // For central domains, continue with normal CSRF verification
        if (!$request->hasSession()) {
            return $next($request);
        }
        
        if (!$request->session()->isStarted()) {
            $request->session()->start();
        }
        
        // Let parent handle CSRF verification
        $response = parent::handle($request, $next);
        
        // Always set a fresh XSRF-TOKEN cookie
        $response->headers->setCookie(
            cookie('XSRF-TOKEN', $request->session()->token(), 120, '/', null, config('session.secure'), false, false, 'lax')
        );
        
        return $response;
    }
}






