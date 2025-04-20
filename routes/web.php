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
        
        // Include auth routes for central domains
        require __DIR__.'/auth.php';
        
        // Protected routes that require email verification
        Route::middleware(['auth', 'verified'])->group(function () {
            Route::get('/dashboard', function () {
                return view('dashboard');
            })->name('dashboard');
            
            // Add other routes that require verification here
        });
        
        // Subdomain routes
        Route::post('/subdomain', [SubdomainController::class, 'store'])->name('subdomain.store');
        
        // Admin routes
        Route::middleware(['auth'])->group(function () {
            Route::get('/admin/subdomain-requests', [SubdomainController::class, 'index'])->name('subdomain.index');
            Route::post('/admin/subdomain/{id}/approve', [SubdomainController::class, 'approve'])->name('subdomain.approve');
            Route::post('/admin/subdomain/{id}/reject', [SubdomainController::class, 'reject'])->name('subdomain.reject');
            
            Route::post('/admin/tenant/{id}/freeze', [SubdomainController::class, 'freeze'])->name('tenant.freeze');
            Route::post('/admin/tenant/{id}/unfreeze', [SubdomainController::class, 'unfreeze'])->name('tenant.unfreeze');
            Route::get('/admin/tenant/{id}/upgrade', [SubdomainController::class, 'upgrade'])->name('tenant.upgrade');
            Route::post('/admin/tenant/{id}/update-plan', [SubdomainController::class, 'updatePlan'])->name('tenant.update-plan.ajax');
        });
    });
}

// Remove these profile routes completely or comment them out
// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php'; moved inside the domain group

// Add a temporary debug route
Route::get('/debug/tenant-schema', function() {
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('tenants');
    $tenants = \App\Models\Tenant::all()->map(function($tenant) {
        return [
            'id' => $tenant->id,
            'attributes' => $tenant->getAttributes(),
            'plan' => $tenant->plan,
            'data' => $tenant->data
        ];
    });
    
    return [
        'columns' => $columns,
        'tenants' => $tenants
    ];
})->middleware(['auth', 'role:admin']);


