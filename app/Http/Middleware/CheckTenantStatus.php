<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (tenant() && tenant()->status === \App\Models\Tenant::STATUS_FROZEN) {
            return response()->view('errors.tenant-frozen', [], 403);
        }

        return $next($request);
    }
}

