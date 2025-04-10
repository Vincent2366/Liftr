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
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
                $request->session()->regenerate();
                
                // For API requests, return a token
                if ($request->expectsJson()) {
                    $user = Auth::user();
                    $token = $user->createToken('tenant-token')->plainTextToken;
                    return response()->json(['token' => $token]);
                }
                
                // For web requests, redirect
                return redirect()->intended(route('tenant.dashboard'));
            }

            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Illuminate\Support\Facades\Log::error('Login error: ' . $e->getMessage());
            
            // Return a more helpful error message
            if ($request->expectsJson()) {
                return response()->json(['error' => $e->getMessage()], 422);
            }
            
            return back()->withErrors(['email' => 'Authentication error: ' . $e->getMessage()]);
        }
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

    /**
     * Handle an API login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiLogin(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            Log::info('Login attempt', [
                'email' => $request->email,
                'remember' => $request->boolean('remember'),
                'tenant' => tenant() ? tenant()->id : 'none'
            ]);
            
            // Find the user directly
            $user = \App\Models\User::where('email', $request->email)->first();
            
            if (!$user) {
                Log::warning('Login failed - user not found', [
                    'email' => $request->email
                ]);
                
                return response()->json([
                    'success' => false,
                    'error' => 'User not found with this email.'
                ], 401);
            }
            
            // Check password manually
            if (!\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
                Log::warning('Login failed - password mismatch', [
                    'email' => $request->email
                ]);
                
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid password.'
                ], 401);
            }
            
            // Ensure session is started
            if (!$request->hasSession() || !$request->session()->isStarted()) {
                $request->session()->start();
            }
            
            // Manual login
            Auth::guard('web')->login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            
            // Generate token
            $token = $user->createToken('auth-token')->plainTextToken;
            
            Log::info('Login successful', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            return response()->json([
                'success' => true,
                'token' => $token,
                'redirect' => route('tenant.dashboard')
            ]);
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 422);
        }
    }
}








