<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubdomainController;

// Make sure this is outside any domain group if you're having issues
Route::post('/subdomain', [SubdomainController::class, 'store'])->name('subdomain.store');

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        // your actual routes
        Route::get('/', function () {
            return view('welcome');
        });
        
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->middleware(['auth', 'verified'])->name('dashboard');
        
        // Make sure the subdomain route is correctly defined with the web middleware group
        Route::post('/subdomain', [SubdomainController::class, 'store'])
            ->middleware('web')  // Ensure web middleware is applied for CSRF protection
            ->name('subdomain.store');
    });
}




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Add these routes for the admin dashboard
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Admin routes for subdomain requests
    Route::get('/admin/subdomain-requests', [SubdomainController::class, 'index'])->name('subdomain.index');
    Route::post('/admin/subdomain-requests/{id}/approve', [SubdomainController::class, 'approve'])->name('subdomain.approve');
    Route::post('/admin/subdomain-requests/{id}/reject', [SubdomainController::class, 'reject'])->name('subdomain.reject');
});

require __DIR__.'/auth.php';






