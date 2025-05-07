<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Verify reCAPTCHA
        $recaptchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);
        
        $recaptchaData = $recaptchaResponse->json();
        
        if (!$recaptchaData['success'] || $recaptchaData['score'] < 0.5) {
            return back()->withErrors([
                'recaptcha' => 'reCAPTCHA verification failed. Please try again.',
            ]);
        }
        
        try {
            $request->authenticate();
            $request->session()->regenerate();
            
            // Flash success message
            session()->flash('success', 'You have been successfully logged in.');
            
            // Use direct route instead of RouteServiceProvider::HOME
            return redirect()->intended(route('dashboard', absolute: false));
        } catch (\Exception $e) {
            // Flash error message
            session()->flash('error', 'Authentication failed. Please check your credentials.');
            return back();
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        
        // Flash success message
        session()->flash('success', 'You have been successfully logged out.');

        return redirect('/');
    }
}




