<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserDomainController extends Controller
{
    public function requestDomain(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'domain' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}$/'],
        ], [
            'domain.regex' => 'Please enter a valid domain name (e.g., example.com).',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = $request->user();
        $user->domain = $request->domain;
        $user->domain_status = 'pending';
        $user->save();

        return back()->with('success', 'Domain request submitted successfully! Awaiting approval.');
    }

    public function approveDomain(Request $request, $userId)
    {
        if (!$request->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $user = User::findOrFail($userId);
        
        // For local development, append .test to the domain
        $localDomain = $user->domain;
        if (!str_ends_with($localDomain, '.test')) {
            $localDomain = str_replace('.com', '.test', $localDomain);
            // If it doesn't have any TLD, add .test
            if (!str_contains($localDomain, '.')) {
                $localDomain .= '.test';
            }
        }
        
        $user->domain = $localDomain;
        $user->domain_status = 'approved';
        $user->save();

        // Create a new tenant with the user's ID
        $tenant = \Stancl\Tenancy\Database\Models\Tenant::create([
            'id' => $user->id,
            // You can add additional tenant data here
            'data' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
        
        // Associate the domain with the tenant
        $tenant->domains()->create(['domain' => $localDomain]);
        
        // Run migrations for the new tenant database
        // This will create all the necessary tables in the tenant database
        \Artisan::call('tenants:migrate', [
            '--tenant' => $tenant->id
        ]);
        
        // Optionally seed the tenant database
        \Artisan::call('tenants:seed', [
            '--tenant' => $tenant->id
        ]);

        return back()->with('success', "Domain approved successfully! The site is available at http://{$localDomain} with its own database.");
    }

    public function rejectDomain(Request $request, $userId)
    {
        if (!$request->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $user = User::findOrFail($userId);
        $user->domain_status = 'rejected';
        $user->save();

        return back()->with('success', 'Domain rejected.');
    }
}




