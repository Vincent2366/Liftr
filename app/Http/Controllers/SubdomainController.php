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

        // Create a subdomain request
        SubdomainRequest::create([
            'subdomain' => $request->subdomain,
            'status' => SubdomainRequest::STATUS_PENDING,
            'user_id' => $user->id,
        ]);

        return back()->with('success', 'Your domain request has been submitted and is pending approval. You will receive an email when it\'s approved.');
    }

    /**
     * Approve a subdomain request
     */
    public function approve($id)
    {
        $subdomainRequest = SubdomainRequest::findOrFail($id);
        
        // Create the tenant
        $tenant = Tenant::create(['id' => $subdomainRequest->subdomain]);
        $tenant->domains()->create(['domain' => $subdomainRequest->subdomain . '.localhost']);
        
        // Update the request status
        $subdomainRequest->update(['status' => SubdomainRequest::STATUS_APPROVED]);
        
        // Generate a random password for the tenant
        $password = Str::random(10);
        
        // Send approval email if user has an email
        if ($subdomainRequest->user && $subdomainRequest->user->email) {
            Mail::to($subdomainRequest->user->email)
                ->send(new SubdomainApproved($subdomainRequest, $password));
        }
        
        return back()->with('success', 'Subdomain request approved and tenant created.');
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
}






