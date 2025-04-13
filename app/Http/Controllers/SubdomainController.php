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
}























