<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\SubdomainRequest;
use Illuminate\Support\Facades\Auth;

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
        ]);

        // Create a subdomain request
        SubdomainRequest::create([
            'subdomain' => $request->subdomain,
            'status' => SubdomainRequest::STATUS_PENDING,
            'user_id' => Auth::id() ?? 1, // Default to user 1 if not logged in
        ]);

        return back()->with('success', 'Your domain request has been submitted and is pending approval.');
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
        
        // Run migrations for the new tenant
        // This would typically be done via a job or command
        
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

