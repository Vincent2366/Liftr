<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TenantAuthController extends Controller
{
    /**
     * Display the tenant login view.
     */
    public function showLoginForm(): View
    {
        // Ensure a fresh session and CSRF token
        if (session()->has('errors')) {
            session()->keep(['errors']);
        }
        
        // Get current tenant
        $tenant = tenant();
        
        return view('tenant.auth.tenantLogin', compact('tenant'));
    }

    /**
     * Handle a tenant login request.
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            // Authentication was successful
            $request->session()->regenerate();
            
            return redirect()->intended(route('tenant.dashboard'));
        }

        // Authentication failed
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    /**
     * Log the user out of the tenant application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

