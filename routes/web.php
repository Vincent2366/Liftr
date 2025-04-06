<?php

use App\Http\Controllers\ProfileController;
use App\Domains\SuperAdmin\Controllers\UserController;
use App\Http\Controllers\UserDomainController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $users = [];
    if (auth()->user() && auth()->user()->isSuperAdmin()) {
        $users = \App\Models\User::where('is_super_admin', false)->get();
    }
    return view('dashboard', compact('users'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Domain management - use full namespace
    Route::post('/request-domain', [UserDomainController::class, 'requestDomain'])->name('user.request-domain');
});

Route::middleware(['auth', \App\Http\Middleware\SuperAdminMiddleware::class])->group(function () {
    Route::post('/admin/approve-domain/{user}', [UserDomainController::class, 'approveDomain'])->name('admin.approve-domain');
    Route::post('/admin/reject-domain/{user}', [UserDomainController::class, 'rejectDomain'])->name('admin.reject-domain');
});

Route::middleware(['auth', \App\Http\Middleware\SuperAdminMiddleware::class])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
});

require __DIR__.'/auth.php';








