<?php

namespace App\Http\Controllers;

use App\Mail\SubdomainApproved;
use App\Models\SubdomainRequest;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SubdomainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = SubdomainRequest::all();
        return view('admin.subdomain-requests', compact('requests'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subdomain' => 'required|alpha_num|min:3|max:20|unique:tenants,id|unique:subdomain_requests,subdomain',
            'email' => 'required|email',
        ]);

        // Check if user exists with this email
        $user = User::where('email', $request->email)->first();
        
        // If not, create a new user
        if (!$user) {
            $user = User::create([
                'name' => $request->subdomain, // Use subdomain as name initially
                'email' => $request->email,
                'password' => Hash::make(Str::random(10)), // Random password, will be reset on approval
            ]);
        }
        
        // Create the subdomain request with email and pending status
        $subdomainRequest = SubdomainRequest::create([
            'subdomain' => $request->subdomain,
            'user_id' => $user->id,
            'email' => $request->email, // Store email directly in subdomain_requests
            'status' => 'pending', // Initialize with pending status
        ]);
        
        // Log the creation
        Log::info('Subdomain request created', [
            'subdomain' => $request->subdomain,
            'email' => $request->email,
            'status' => 'pending'
        ]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Approve a subdomain request
     */
    public function approve($id)
    {
        $subdomainRequest = SubdomainRequest::findOrFail($id);
        
        // Update the status to approved
        $subdomainRequest->status = 'approved';
        $subdomainRequest->save();
        
        // Generate a random password for the tenant
        $password = Str::random(10);
        
        try {
            // Store the email and password in the tenant metadata
            $tenant = Tenant::create([
                'id' => $subdomainRequest->subdomain,
                'email' => $subdomainRequest->email, // Add email to tenant metadata
                'password' => $password
            ]);
            
            $tenant->domains()->create(['domain' => $subdomainRequest->subdomain . '.localhost']);
            
            // Log the approval with detailed information
            Log::info('Subdomain request approved and tenant created', [
                'id' => $subdomainRequest->id,
                'subdomain' => $subdomainRequest->subdomain,
                'email' => $subdomainRequest->email,
                'status' => $subdomainRequest->status
            ]);
            
            // Send approval email
            $emailToUse = $subdomainRequest->email ?? ($subdomainRequest->user->email ?? null);
            
            if ($emailToUse) {
                try {
                    Mail::to($emailToUse)
                        ->send(new SubdomainApproved($subdomainRequest, $password));
                    
                    Log::info('Approval email sent', [
                        'subdomain' => $subdomainRequest->subdomain,
                        'email' => $emailToUse
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send approval email', [
                        'subdomain' => $subdomainRequest->subdomain,
                        'email' => $emailToUse,
                        'error' => $e->getMessage()
                    ]);
                }
            } else {
                Log::warning('No email found to send approval notification', [
                    'subdomain' => $subdomainRequest->subdomain
                ]);
            }
            
            return back()->with('success', 'Subdomain request approved and tenant created.');
        } catch (\Exception $e) {
            Log::error('Failed to create tenant', [
                'subdomain' => $subdomainRequest->subdomain,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Failed to create tenant: ' . $e->getMessage());
        }
    }

    /**
     * Reject a subdomain request
     */
    public function reject($id)
    {
        $subdomainRequest = SubdomainRequest::findOrFail($id);
        $subdomainRequest->update(['status' => SubdomainRequest::STATUS_REJECTED]);
        
        return back()->with('success', 'Subdomain request rejected.');
    }

    /**
     * Freeze a tenant domain
     */
    public function freeze($id)
    {
        $tenant = Tenant::findOrFail($id);
        
        // Update the tenant status to frozen
        $tenant->update(['status' => Tenant::STATUS_FROZEN]);
        
        return redirect()->route('dashboard')->with('success', "Tenant {$tenant->id} has been frozen");
    }

    /**
     * Unfreeze a tenant domain
     */
    public function unfreeze($id)
    {
        $tenant = Tenant::findOrFail($id);
        
        // Update the tenant status to active
        $tenant->update(['status' => Tenant::STATUS_ACTIVE]);
        
        return redirect()->route('dashboard')->with('success', "Tenant {$tenant->id} has been unfrozen");
    }

    /**
     * Show upgrade options for a tenant
     */
    public function upgrade($id)
    {
        $tenant = Tenant::findOrFail($id);
        return view('admin.tenant-upgrade', compact('tenant'));
    }

    /**
     * Update tenant plan
     */
    public function updatePlan(Request $request, $id)
    {
        // Enable query logging
        \DB::enableQueryLog();
        
        \Log::info('Update plan request received', [
            'tenant_id' => $id,
            'plan' => $request->plan,
            'request_data' => $request->all()
        ]);
        
        try {
            $tenant = Tenant::findOrFail($id);
            $oldPlan = $tenant->plan ?? 'free';
            $newPlan = $request->plan;
            
            \Log::info('Current tenant data before update', [
                'tenant_id' => $tenant->id,
                'current_plan' => $oldPlan,
                'attributes' => $tenant->getAttributes()
            ]);
            
            // Direct update using query builder to ensure it's saved
            \DB::table('tenants')
                ->where('id', $id)
                ->update(['plan' => $newPlan, 'updated_at' => now()]);
                
            // Log the SQL query for debugging
            \Log::info('SQL query executed', [
                'query' => \DB::getQueryLog()[count(\DB::getQueryLog())-1] ?? 'Query logging not enabled'
            ]);
            
            // Force refresh the tenant model to get updated data
            $tenant = Tenant::findOrFail($id);
            
            \Log::info('Tenant data after direct update', [
                'tenant_id' => $tenant->id,
                'new_plan' => $tenant->plan,
                'attributes' => $tenant->getAttributes()
            ]);
            
            // If downgrading from premium or ultimate to free, handle user limits
            if (($oldPlan == 'premium' || $oldPlan == 'ultimate') && $newPlan == 'free') {
                // Run this in the tenant context
                tenancy()->initialize($tenant);
                
                // Get all users
                $users = \App\Models\User::orderBy('created_at', 'asc')->get();
                
                // Keep the oldest 3 users active, disable the rest
                $activeUsers = $users->take(3);
                $disabledUsers = $users->slice(3);
                
                // Soft-disable extra users
                foreach ($disabledUsers as $user) {
                    $user->update(['status' => 'disabled']);
                    
                    \Log::info('User disabled due to plan downgrade', [
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'tenant' => $tenant->id
                    ]);
                }
                
                tenancy()->end();
            }
            
            // If upgrading from free to premium or ultimate, reactivate disabled users
            if ($oldPlan == 'free' && ($newPlan == 'premium' || $newPlan == 'ultimate')) {
                // Run this in the tenant context
                tenancy()->initialize($tenant);
                
                // Reactivate all disabled users
                $disabledUsers = \App\Models\User::where('status', 'disabled')->get();
                $reactivatedCount = $disabledUsers->count();
                
                foreach ($disabledUsers as $user) {
                    $user->update(['status' => 'active']);
                    
                    \Log::info('User reactivated due to plan upgrade', [
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'tenant' => $tenant->id
                    ]);
                }
                
                \Log::info('Tenant upgraded from free plan', [
                    'tenant' => $tenant->id,
                    'new_plan' => $newPlan,
                    'reactivated_users_count' => $reactivatedCount
                ]);
                
                tenancy()->end();
            }
            
            return response()->json([
                'success' => true, 
                'message' => 'Plan updated successfully',
                'plan' => $newPlan
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating tenant plan', [
                'tenant_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Error updating plan: ' . $e->getMessage()
            ], 500);
        }
    }
}



































