<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Tenant\TenantAuthController;
use App\Http\Controllers\Tenant\TenantRegisterController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\UserController;
use App\Http\Controllers\Tenant\SessionController;
use App\Http\Controllers\Tenant\ClientController;
use App\Http\Controllers\Tenant\ProfileController;
use App\Http\Controllers\Tenant\SettingController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Tenant\AppointmentController;

// CSRF cookie route with proper middleware
Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'web',
])->get('/csrf-cookie', function (Request $request) {
    $response = response()->json(['message' => 'CSRF cookie set']);
    
    // Explicitly set the XSRF-TOKEN cookie with the proper domain
    $response->headers->setCookie(
        cookie(
            'XSRF-TOKEN', 
            $request->session()->token(), 
            120, 
            '/', 
            config('session.domain'), // Use the configured session domain
            config('session.secure'), 
            false, 
            false, 
            config('session.same_site')
        )
    );
    
    return $response;
});

// Main tenant routes
Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'web',
])->group(function () {
    // Tenant Auth routes
    Route::get('/login', [TenantAuthController::class, 'showLoginForm'])
        ->middleware(['check.tenant.status'])
        ->name('tenant.login');

    Route::post('/login', [TenantAuthController::class, 'login']);
    Route::post('/logout', [TenantAuthController::class, 'logout'])->name('logout');

    // Add tenant registration routes
    Route::get('/register', [TenantRegisterController::class, 'showRegistrationForm'])
        ->middleware(['check.tenant.status'])
        ->name('tenant.register');
    Route::post('/register', [TenantRegisterController::class, 'register']);

    // Keep the password reset routes using the original controller
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
});

// Protected tenant routes using standard session auth
Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'web',
    'auth:tenant', // Keep the guard name but it now uses session driver
    'check.tenant.status'
])->group(function () {
    // Dashboard - redirect based on role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('tenant.dashboard');
    
    // Admin routes
    Route::middleware(['role:Admin'])->group(function () {
        // Resource routes for admins
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
    });
    
    // User dashboard route
    Route::get('/user-dashboard', [DashboardController::class, 'userDashboard'])->name('tenant.user.dashboard');

    // Appointments
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('tenant.appointments.store');

    // Profile and settings - available to all authenticated users
    Route::get('/profile', [ProfileController::class, 'index'])->name('tenant.profile');
    Route::get('/settings', [SettingController::class, 'index'])->name('tenant.settings');
});

// Admin-only routes
Route::middleware(['auth:tenant', 'role:Admin'])->group(function () {
    // Appointments management
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('tenant.appointments');
    Route::patch('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('tenant.appointments.update');
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('tenant.appointments.destroy');
});

// Test route to check if user exists
Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    'web',
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













