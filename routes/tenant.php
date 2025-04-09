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

// Add this route to provide CSRF token for SPA authentication
Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie set']);
});

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    // Tenant Auth routes
    Route::get('/login', [TenantAuthController::class, 'showLoginForm'])
        ->middleware(['check.tenant.status'])
        ->name('login');
    Route::post('/login', [TenantAuthController::class, 'login']);
    Route::post('/logout', [TenantAuthController::class, 'logout'])->name('logout');
    
    // Keep the password reset routes using the original controller
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    
    // Protected tenant routes
    Route::middleware(['auth', 'check.tenant.status'])->group(function () {
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
});








