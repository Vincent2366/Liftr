<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckTenantStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if tenant is frozen
        if (tenant() && tenant()->status === \App\Models\Tenant::STATUS_FROZEN) {
            return response()->view('errors.tenant-frozen', [], 403);
        }
        
        // Check if authenticated user is disabled (for free plan limits)
        if (Auth::guard('tenant')->check()) {
            $user = Auth::guard('tenant')->user();
            if ($user->status === 'disabled') {
                Auth::guard('tenant')->logout();
                return response()->view('errors.user-disabled', [
                    'message' => 'Your account has been disabled due to plan limitations. Please contact your administrator.'
                ], 403);
            }
        }

        return $next($request);
    }
}


