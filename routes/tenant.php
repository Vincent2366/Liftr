<?php

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Tenant\TenantAuthController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\UserController;
use App\Http\Controllers\Tenant\SessionController;
use App\Http\Controllers\Tenant\ClientController;
use App\Http\Controllers\Tenant\ProfileController;
use App\Http\Controllers\Tenant\SettingController;
use App\Http\Controllers\Auth\PasswordResetLinkController;

// CSRF cookie route with proper middleware
Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'web',
    'disable.csrf',
])->get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie set']);
});

// Add a route to refresh CSRF token
Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'web',
    'disable.csrf',
])->get('/refresh-csrf', function () {
    return response()->json(['token' => csrf_token()]);
});

// Main tenant routes
Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'web',
    'disable.csrf',
])->group(function () {
    // Tenant Auth routes
    Route::middleware([
        \Illuminate\Session\Middleware\StartSession::class,
    ])->group(function () {
        Route::get('/login', [TenantAuthController::class, 'showLoginForm'])
            ->middleware(['check.tenant.status'])
            ->name('login');
        
        Route::post('/login', [TenantAuthController::class, 'login']);
        Route::post('/api/login', [TenantAuthController::class, 'apiLogin']);
        Route::post('/logout', [TenantAuthController::class, 'logout'])->name('logout');
        
        // Keep the password reset routes using the original controller
        Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    });
});

// Protected tenant routes using Sanctum
Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'auth:tenant',
    'check.tenant.status'
])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('tenant.dashboard');
    
    // Resource routes
    Route::resource('users', UserController::class)->names([
        'index' => 'tenant.users',
        'show' => 'tenant.users.show',
        'create' => 'tenant.users.create',
        'store' => 'tenant.users.store',
        'edit' => 'tenant.users.edit',
        'update' => 'tenant.users.update',
        'destroy' => 'tenant.users.destroy',
    ]);
    
    Route::resource('sessions', SessionController::class)->names([
        'index' => 'tenant.sessions',
        'show' => 'tenant.sessions.show',
        'create' => 'tenant.sessions.create',
        'store' => 'tenant.sessions.store',
        'edit' => 'tenant.sessions.edit',
        'update' => 'tenant.sessions.update',
        'destroy' => 'tenant.sessions.destroy',
    ]);
    
    Route::resource('clients', ClientController::class)->names([
        'index' => 'tenant.clients',
        'show' => 'tenant.clients.show',
        'create' => 'tenant.clients.create',
        'store' => 'tenant.clients.store',
        'edit' => 'tenant.clients.edit',
        'update' => 'tenant.clients.update',
        'destroy' => 'tenant.clients.destroy',
    ]);
    
    // Profile and settings
    Route::get('/profile', [ProfileController::class, 'index'])->name('tenant.profile');
    Route::get('/settings', [SettingController::class, 'index'])->name('tenant.settings');
});

// API-style login route (no CSRF)
Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'web', // Add web middleware to ensure session is available
])->post('/api/login', [TenantAuthController::class, 'apiLogin']);

// Test route to check if user exists
Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'web', // Add web middleware to ensure session is available
])->get('/check-user/{email}', function ($email) {
    $user = \App\Models\User::where('email', $email)->first();
    if ($user) {
        return response()->json([
            'exists' => true,
            'email' => $user->email,
            'id' => $user->id
        ]);
    }
    return response()->json(['exists' => false]);
});



