<?php

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    // Apply the check.tenant.status middleware specifically to the login route
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->middleware(['check.tenant.status'])
        ->name('login');
    
    // Other auth routes without the check
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    
    // Include other auth routes as needed
    Route::get('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])
        ->name('password.email');
    
    // Other tenant routes...
    Route::get('/', function () {
        return view('tenant.dashboard');
    });
});


