<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class TenantAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('tenant.auth.tenantLogin');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Log the login attempt (optional)
        \Log::info('Login attempt', [
            'email' => $credentials['email'],
            'remember' => $request->boolean('remember'),
            'tenant' => tenant() ? tenant()->id : 'none'
        ]);

        if (Auth::guard('tenant')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Log successful login (optional)
            \Log::info('Login successful', [
                'user_id' => Auth::guard('tenant')->id(),
                'email' => $credentials['email']
            ]);
            
            // If it's an AJAX request or JSON is expected, return JSON
            if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'redirect' => route('tenant.dashboard')
                ]);
            }
            
            // Otherwise, redirect
            return redirect()->intended(route('tenant.dashboard'));
        }

        // If it's an AJAX request or JSON is expected, return JSON
        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'error' => 'The provided credentials do not match our records.'
            ], 401);
        }

        // Otherwise, redirect back with error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }
        
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }

    // Remove or modify the apiLogin method if you no longer need it
    // If you want to keep it for API clients, modify it to use session auth
    public function apiLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('tenant')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::guard('tenant')->user();
            
            return response()->json([
                'success' => true,
                'user' => $user,
                'redirect' => route('tenant.dashboard')
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => 'Invalid credentials'
        ], 401);
    }
}











