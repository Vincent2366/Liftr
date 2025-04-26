<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tenant;
use Symfony\Component\HttpFoundation\Response;

class CheckFreePlanUserLimit
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (tenant() && tenant()->plan === Tenant::PLAN_FREE) {
            $userCount = User::count();
            
            if ($userCount >= 3 && $request->route()->getName() === 'tenant.register') {
                return response()->view('tenant.errors.user-limit', [
                    'message' => 'Free plan is limited to 3 users. Please upgrade to add more users.'
                ], 403);
            }
        }

        return $next($request);
    }
}
