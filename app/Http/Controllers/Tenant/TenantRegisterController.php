<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class TenantRegisterController extends Controller
{
    /**
     * Display the registration view.
     */
    public function showRegistrationForm()
    {
        return view('tenant.auth.tenantRegister');
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Check if tenant is on free plan and already has 3 users
        if (tenant()->plan === \App\Models\Tenant::PLAN_FREE) {
            $userCount = User::count();
            if ($userCount >= 3) {
                return response()->view('tenant.errors.user-limit', [
                    'message' => 'Free plan is limited to 3 users. Please upgrade to add more users.'
                ], 403);
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'User', // Default role for registered users
        ]);

        event(new Registered($user));

        Auth::guard('tenant')->login($user);

        return redirect()->route('tenant.dashboard');
    }
}

