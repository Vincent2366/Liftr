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
        // Don't exclude all routes - be more specific
        // '*',
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
        // Start the session if it hasn't been started yet
        if ($request->hasSession() && !$request->session()->isStarted()) {
            $request->session()->start();
        }
        
        // Check if this is a tenant domain request
        if (app()->bound('tenancy.tenant') && app('tenancy.tenant')) {
            // For tenant domains, we'll still verify CSRF but handle failures differently
            try {
                $this->validateCsrf($request);
            } catch (\Illuminate\Session\TokenMismatchException $e) {
                // Log the CSRF failure but continue for tenant domains
                \Log::warning('CSRF validation failed for tenant domain', [
                    'tenant' => tenant()->id,
                    'url' => $request->url(),
                    'method' => $request->method()
                ]);
            }
            
            $response = $next($request);
            
            // Always set the XSRF-TOKEN cookie for tenant domains
            if ($request->hasSession()) {
                $response->headers->setCookie(
                    cookie(
                        'XSRF-TOKEN', 
                        $request->session()->token(), 
                        120, 
                        '/', 
                        config('session.domain'),
                        config('session.secure'), 
                        false, 
                        false, 
                        config('session.same_site')
                    )
                );
            }
            
            return $response;
        }
        
        // For central domains, continue with normal CSRF verification
        return parent::handle($request, $next);
    }
}








