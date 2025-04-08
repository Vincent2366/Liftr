<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubdomainController;

foreach (config('tenancy.central_domains') as $domain) {
    Route::domain($domain)->group(function () {
        // your actual routes
        
        Route::get('/', function () {
            return view('welcome');
        });
        
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->middleware(['auth', 'verified'])->name('dashboard');
        
        // Subdomain routes
        Route::post('subdomain', [SubdomainController::class, 'store'])->name('subdomain.store');
        
        // Admin routes
        Route::middleware(['auth', 'verified'])->group(function () {
            Route::get('/subdomain-requests', [SubdomainController::class, 'index'])->name('subdomain.requests');
            Route::post('/subdomain/{id}/approve', [SubdomainController::class, 'approve'])->name('subdomain.approve');
            Route::post('/subdomain/{id}/reject', [SubdomainController::class, 'reject'])->name('subdomain.reject');
        });
    });
}

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

